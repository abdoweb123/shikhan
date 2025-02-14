<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App;
use App\Exports\membersExport;
use App\course_test_result;
use App\course;
use App\member;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Services\GlobalService;
// use App\Services\UserService;
use App\Services\ExtraTrays;
use Anam\PhantomMagick\Converter;
use Anam\PhantomLinux\Path;
use App\MemberExtraTray;

class members extends Controller
{
    public $range_degree=1;
    private $globalService;


    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }

    public function index(Request $request)
    {

        ini_set('memory_limit', -1);
        $data['get'] = $request->input();
        $data['result'] = member::with(['courses'])
          ->select('members.id','ps','name','email','phone','status','created_at')
          ->orderBy('members.id', 'ASC');

        if (!empty($data['get']['term'])) {
          $data['result']->where('email','like','%'.$data['get']['term'].'%');
        }
        $data['result'] = $data['result']->paginate(300);

        return view ('back.content.members.index',$data);

    }

    public function getUsersInfo(Request $request)
    {

        // ini_set('memory_limit', -1);
        $data['get'] = $request->input();

        $data['result'] = member::with('test_results.course.sites')->select('id','name','email','phone','status','created_at','deleted_at')->withTrashed()->orderBy('members.id', 'ASC');

        if (!empty($data['get']['term'])) {
          $data['result']
            ->where('email','like','%'.$data['get']['term'].'%')
            ->orwhere('name','like','%'.$data['get']['term'].'%')
            ->orwhere('phone','like','%'.$data['get']['term'].'%');
        }

        $data['result'] = $data['result']->paginate(10);

        return view ('back.content.members.details',$data);

    }

    public function resetPassword(Request $request)
    {
        if(! $request->user_id){
          return back();
        }

        member::findOrFail($request->user_id)->update(['password'=>'$2y$10$lxIY7xRPlwoibirQKVHl3.aKjsVbo/UYwLaoYg0ks/ILHmaDN.AM6']);

        return back()->with('success', 'Member Password Updated Successfully!');
    }

    public function getUserDetails(Request $request)
    {

          $user = member::where('id', $request->params['userId'])->first();
          if (!$user){
            return response()->json('');
          }

          // اختبارات الطالب
          if ($request->params['detailsType'] == 'USER_COURSES') {
              return response()->json([
                'data' => $this->globalService->renderUserCoursesMaxDegrees($user)
              ]);
          }

          // الدورات التى لم يشترك بها الطالب
          if ($request->params['detailsType'] == 'USER_COURSES_DOESNT_SUBSCRIPE') {
              return response()->json([
                'data' => $this->globalService->renderCoursesUserDoesntSubscripeIn($user)
              ]);
          }

          // الدورات التى لم يختبرها الطالب
          if ($request->params['detailsType'] == 'USER_COURSES_ACTIVE_NOT_TESTED') {
              return response()->json([
                'data' => $this->globalService->renderCoursesNotTestedForUser($user)
              ]);
          }

          // اشتراكات الطالب
          if ($request->params['detailsType'] == 'USER_SUBSCRIPTIONS') {
              return response()->json([
                'data' => $this->globalService->renderUserSubscriptions($user)
              ]);
          }

          if ($request->params['detailsType'] == 'USER_TEST_RESULT_ANSWERS') {
              return response()->json([
                'data' => $this->globalService->renderUserTestResultAnswers($request->params)
              ]);
          }



          // compare user cources results between dynamic function
          if ($request->params['detailsType'] == 'USER_COMPARE_COURSES') {

              $user = member::where('id', $request->params['userId'])->first();

              $dynamicService = new \App\Services\UserResultsService();
              $dynamicDetails = $dynamicService->setUser( $user )->getUserCoursesTestsResults();

              $staticService = new \App\Services\ResultsService();
              $staticDetails =  $staticService->setUser( $user )->getFinalUserCourseResult();

              return response()->json([
                'data' => view('common.members.user-courses', ['detailsType' => 'USER_COMPARE_COURSES', 'dynamicDetails' => $dynamicDetails, 'staticDetails' => $staticDetails])->render()
              ]);

          }


          // return view('front.reports_global.sta_search_user_courses',compact('report'));


    }

    public function create()
    {
        return view ('back.content.members.create');
    }

    public function loginWithOutPassword(Request $request)
    {
        session(['user_id_to_login' => $request->user_id]);
        return redirect()->route('front.login_user');
    }

    public function changeUserStatus(Request $request)
    {
        if(! $request->user_id){
          return back();
        }

        $user = member::withTrashed()->findOrFail($request->user_id);
        if ($user->deleted_at){
          $user->restore();
        } else {
          $user->delete();
        }


        return back()->with('success', 'Member Disabled!');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email',Rule::unique('members')],
            'password' => 'confirmed|string|min:6|required',
            'avatar' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'name' => 'required',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string|min:11',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $avatar = '';
        if (!empty($request->avatar)) {
            $avatar = $request->avatar->store('members');
        }

        $save = member::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone',null),
            'birthday' => $request->input('birthday',null),
            'gender' => $request->input('gender'),
            'password' => bcrypt($request->input('password','123456')),
            'created_by' => Auth::guard('admin')->user()->id,
            'avatar' => $avatar,
            'provider' => 0,
            'status' => 0,
        ]);

        return redirect()->route('dashboard.members.create')->with('success', 'Member Added Successfully!');

    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request)
    {
        $id = \Route::input('member');
        $data['fields'] = member::where(['id' => $id])->firstOrFail();
        return view ('back.content.members.edit',$data);
    }

    public function update(Request $request)
    {

        $id = \Route::input('member');
        $validator = Validator::make($request->all(), [
            'email' => ['required','email',Rule::unique('members')->ignore($id)],
            'password' => 'confirmed'.((is_numeric($id))?'':'|string|min:6|required'),
            'avatar' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'name' => 'required',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string', // |min:11
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $save = member::where(['id' => $id])->firstOrFail();

        $save->name = $request->input('name');
        $save->email = $request->input('email');
        $save->phone = $request->input('phone',null);
        $save->birthday = $request->input('birthday',null);
        $save->gender = $request->input('gender');

        if (!empty($request->input('password')))
        {
            $save->password = bcrypt($request->input('password'));
            $save->ps = '';
        }

        if (!empty($request->avatar))
        {
            if (!empty($save->avatar))Storage::delete(public_path('/'.$save->avatar));
            $save->avatar = $request->avatar->store('members');
        }

        $save->updated_by = Auth::guard('admin')->user()->id;

        $save->save();


        return redirect()->route('dashboard.users.info')->with('success', 'Member updated Successfully!');
    }

    public function status(Request $request)
    {
        $id = \Route::input('member');
        $status = $request->input('status');

        $save = member::findOrFail($id);
        $save->status = intval($status);
        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();

        return redirect()->route('dashboard.members.index')->with('success', $status ? 'Member Enabled Successfully!' : 'Member Disabled Successfully!');
    }

    public function destroy(Request $request)
    {
        $id = \Route::input('member');

        $row = member::where(['status' => 0,'id' => $id])->firstOrFail();
        $row->delete();

        return redirect()->route('dashboard.members.index')->with('success', 'Member deleted Successfully!');
    }

    public function createUserPassword(Request $request)
    {
        $member = member::findOrFail($request->user_id);
        $randomePassword = generateRandomString(8);
        $member->ps = $randomePassword;
        $member->password = bcrypt($randomePassword);
        $member->save();
        return back();
    }

    public function createUsersPasswords()
    {

        member::where('admin','!=' , 1)->orderBy('id')->chunk(10, function ($members) {
              foreach ($members as $member) {
                $randomePassword = generateRandomString(8);
                $member->ps = $randomePassword;
                $member->password = bcrypt($randomePassword);
                $member->save();
              }
        });

        return redirect()->route('dashboard.members.index');
    }

    public function sendUsersPasswords()
    {

        app()->setLocale('ar');
        member::where('admin','!=' , 1)->wherein('id',[2,4836])->orderBy('id')->chunk(5, function ($members) {
              foreach ($members as $row) {

                $send = 0;
                $return = $this->send_prossess($row);
                if($return['status'] == 'success'){$send++;}

                if ($send > 0){
                      $flash_data = ['status' => 'success','message' => $send.' passwords have been sent to members'];
                } else {
                      $flash_data = ['status' => 'error','message' => 'A problem occurred while sending the passwords!'];
                }

            }
        });

        app()->setLocale('en');
        return redirect()->route('dashboard.members.index');

    }

    private function send_prossess($row)
    {

        \Mail::send('emails.send_password', ['data' => $row],
        function ($mail) use ($row)
        {
            $mail
            ->from(config('mail.from.address'),config('mail.from.name'))
            ->to($row->email,$row->name)->subject( __('core.send_user_password_subject') );
        });

        if(empty(\Mail::failures())) {
            $return = ['status' => 'success','message' => 'Passwords has been sended Successfully!'];
        } else {
            $return = ['status' => 'error','message' => 'A problem occurred while sending the passwords!'];
            // dd(\Mail::failures());
        }
        return $return;
    }

    public function export_(Request $request)
    {
       $members = member::get();
       // dd($members);
      $type ='xlsx';

        $return = (new membersExport());
        $extensions = config('excel.extension_detector');

        if(in_array($type,array_keys($extensions)))
        {
            return $return->download('membersExport.'.$type,$extensions[$type]);
        }
        else
        {
            $return->download('membersExport.csv',\Maatwebsite\Excel\Excel::CSV);
        }
    }

    public function certificates_show_admin(Request $request)
    {


      if ($request->type != 'jpg' && $request->type != 'pdf'){
        return redirect()->route('certificates');
      }

      $language = App::getLocale() ;
      $testResultId = explode( '-', \Route::input('id') )[0];
      $siteId = explode( '-', \Route::input('id') )[1];
      $userId = $request->user_id;

      $courseTestResult = App\member::find($userId)->test_results()->where('id','=',$testResultId)->firstOrFail();


      if ($courseTestResult->degree < $this->rangeDegree){
          return back()->withErrors(['', __('trans.less_than_70')]);
      }

      $course = $courseTestResult->course;
      $message = $course->translate($courseTestResult->locale);
      $site = $course->sites()->where('site_id',$siteId)->select('title','certificate_template_name')->first();
      if(! $site){
        return back()->withErrors(['','برجاء الرجوع لإدارة الموقع']);
      }

      $examAtHijri = $this->getExamAtHijri($courseTestResult->created_at);



      if ($request->type == 'jpg'){
          $courseTemplate = $this->getCertificatesTemplates($site->certificate_template_name.'_jpg', $language); //  'certificate_template_with_sig_jpg'
          $message->content = $courseTemplate;

          return response()->json(['data' => view('certificates.course_jpg', [
              'data' => $courseTestResult,
              'exam_at_hijri' => $examAtHijri,
              'subject' => $message->subject,
              'content' => $message->content,
              'course' => $course,
              'site' => $site
            ])->render()
          ]) ;
      }

      if ($request->type == 'pdf'){
          $courseTemplate = $this->getCertificatesTemplates($site->certificate_template_name.'_pdf', $language); // 'certificate_template_with_sig_pdf'
          $message->content = $courseTemplate;

          $content = view('certificates.course_pdf', [
            'data' => $courseTestResult,
            'exam_at_hijri' => $examAtHijri,
            'subject' => $message->subject,
            'content' => $message->content,
            'course' => $course,
            'site' => $site
          ]);


          $conv = new Converter();
          $options = [
            'format' => $course->format,
            'orientation' => $course->orientation,
            'margin' => '.1cm',
          ];
          $time=time();
          $conv->setPdfOptions($options)->addPage($content)
            ->setBinary(base_path('vendor/anam/phantomjs-2.1.1-linux-x86_64/bin/phantomjs'))
            ->save(storage_path('app/public/public/certificates/'.$language.'-'.$courseTestResult->id.'-'.$time.'.pdf'));

          return response()->download( storage_path('app/public/public/certificates/'.$language.'-'.$courseTestResult->id.'-'.$time.'.pdf') )->deleteFileAfterSend(true);
      }

    }


    public function showExtraTrays(Request $request)
    {

        $member = member::where('id', $request->member)->select('id','email','name')->firstOrFail();
        $courses = course::select('id','title')->orderBy('title')->get();

        $extraTraysService = new ExtraTrays(['user' => $member]);
        $extraTrays = $extraTraysService->getUserExtraTrays($member);

        return view('back.content.extra_trays.index', compact(['member','extraTrays','courses']));

    }

    public function storeExtraTrays(Request $request)
    {

        abort_if(! member::where('id', $request->user_id)->exists(), 404);

        MemberExtraTray::updateOrCreate(
            ['user_id' => $request->user_id, 'course_id' => $request->course_id],
            ['trays' => $request->trays]
        );

        return redirect()->back()->with('success', 'تمت الاضافة');
    }

    public function updateExtraTrays(Request $request)
    {

        MemberExtraTray::where('id', $request->extra_tray_id)->update([
          'trays' => $request->trays
        ]);

        return redirect()->back()->with('success', 'تمت التعديل');

    }


}
