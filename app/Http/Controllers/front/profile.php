<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use App\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App;
use Session;
use DB;
use App\site;
use App\course;
use App\course_site;
use App\course_test_result;
use App\MemberSiteCertificate;
use App\member;
use App\Services\GlobalService;
use App\Services\CountryService;
use App\Services\PartnerCodeService;
use Validator;


class profile extends Controller
{
    public $range_degree=1;
    private $userResultsService;
    private $userStatisticsService;

    public function __construct(private GlobalService $globalService, private CountryService $countryService, private PartnerCodeService $partnerCodeService)
    {
        // $this->userResultsService = new App\Services\UserResultsService();
        $this->userResultsService = new App\Services\UserResultsServiceStatic();

        $this->userStatisticsService = new App\Services\UserStatisticsServiceStatic();
    }

    public function index(Request $request)
    {
        $lang = App::getLocale() ;
        $id = Auth::guard('web')->user()->id ;
        $data = $this->data($request,'profile');
        $data['countries'] = $this->countryService->filter();
        $data['fields'] = member::find($id);

        if ($request->isMethod('post'))
        {
            $this->validate($request, [
                'email' => ['required','email',Rule::unique('members')->ignore($id)],
                'password' => 'confirmed'.((is_numeric($id))?'':'|string|min:6|required'),
                'avatar' => 'file|image|mimes:jpeg,jpg,png|max:1024',
                'birthday' => 'nullable|date|date_format:Y-m-d|before:today',
                'gender' => 'required|in:1,2',
                // 'name' => 'required|string|max:50',
                'name_lang' => 'nullable|string|max:50',
                'country_id' => 'nullable|numeric',
                'phone' => 'nullable|string',
                'id_number' => 'nullable|string|max:25',
                'id_image' => 'nullable|file|image|mimes:jpeg,jpg,png|max:1024',
                // 'code' => 'nullable|string|max:10',
                'amount' => 'nullable|numeric|min:1',
                'pay_image' => 'nullable|file|image|mimes:jpg,png,jpeg|max:1024',
              ],
                $messages = [
                  'amount' =>  __('messages.amount_must_be'),
                  'pay_image' =>  __('messages.pay_image_ruls'),
              ]);



            // if ($request->code){
            //     $this->validateCodeExistsActive($request->code);
            //
            //     $this->validateCodeUsed($request->code);
            // }

            $this->validatePayImageIfAmountExists($request->amount, ($request->pay_image ? $request->pay_image : null));


            if($request->id_number){
              if(! Auth::user()->id_image){
                if(! $request->hasFile('id_image')){
                    return back()->withErrors(['msg' => __('trans.please_enter_id_image')]);
                }
              }
            }

            if($this->userMustChangeName()){
                $this->validate($request, [
                    'name' => ['required', 'string', 'max:50', 'min:'.config('project.max_user_name_chr')],
                ]);
            }

            $data['userMustChangeName'] = $this->userMustChangeName();

            $fields = $request->input();
            $save = $data['fields'];
            $save->country_id = $request->country_id;
            if($this->userMustChangeName()){
              $save->name = $fields['name'];
            }
            // $save->name_lang = $fields['name_lang'];
            // if(! $data['fields']->name_lang){
            //   $save->name_lang = $fields['name_lang'];
            // } else {
            //   $save->name_lang = $fields['name_lang'];
            // }
            $save->qualification = $fields['qualification'];
            if($save->email != $request->email){
              $save->email = $fields['email'];
              $save->email_verified_at = null;
            }

            $save->phone = $fields['phone'];
            $save->birthday = $fields['birthday'];
            $save->gender = $fields['gender'];
            $save->updated_by = '0';
            $save->id_number = $fields['id_number'];

            if (!empty($fields['password'])){
                $save->password = bcrypt($fields['password']);
            }


            // if ($request->code){
            //   if (! $save->partner_code){
            //     $save->partner_code = $request->code;
            //     $save->free_status = member::SUSPEND;
            //   }
            // }

            if ($save->isNotPaid()){
                $save->pay_amount = $request->amount;
                $save->free_status = $request->amount ? member::PAID_SUSPENDED : member::NOT_PAID;


                if ($request->hasFile('pay_image')) {
                  $save->pay_image = $request->pay_image->storeAs('pay_images', $save->id . '_' . $request->pay_image->hashName() . '.' . $request->pay_image->extension());
                  $save->free_status = member::PAID_SUSPENDED;
                }
            }



            if (!empty($request->avatar)){
                if (!empty($data['fields']->avatar))Storage::delete(public_path().'/members/'.$data['fields']->avatar);
                $save->avatar = $request->avatar->store('members');
            }

            if (!empty($request->id_image)){
                if (!empty($data['fields']->id_image))Storage::delete(public_path().'/members_ids/'.$data['fields']->id_image);
                $save->id_image = $request->id_image->storeAs('members_ids', $save->id . '_' . $request->file('id_image')->hashName() . '.' . $request->file('id_image')->extension());
            }

            $save->save();

            Session::flash('success', __('message.message_saved'));

            return redirect(route('profile'));
        } else {
            return view('front.content.profile.index',$data);
        }

    }


    private function validatePayImageIfAmountExists($amount, $payImage)
    {
        if (! $amount){
          return;
        }

        if ($amount && !$payImage){
          throw ValidationException::withMessages(['pay_image' => __('messages.enter_pay_image') ]);
        }
    }


    public function userMustChangeName()
    {
        return true;
        // return mb_strlen(auth()->user()->name) <= 13;
    }

    public function clearMyPhoto(Request $request)
    {
        Auth::user()->update(['avatar' => '']);
        Session::flash('photo_deleted', 'تم حذف الصورة');
        return redirect()->back();
    }






    // courses cirts
    public function getCoursesCertificates(Request $request)
    {

        $site = site::where('id', $request->site_id)->firstorfail();

        $myCoursesDetails = $this->userResultsService->setUser(Auth::User())->setSite($site)->getUserCoursesTestsResults();

        if($request->ajax()){
          return response()->json([
            'body' => view('front.content.certificates.site_courses_results_content',['results' => $myCoursesDetails['results'] ])->render()
          ]);
        }

        return view('front.content.certificates.site_courses_results',['results' => $myCoursesDetails['results'], 'site' => $myCoursesDetails['site'] ]);

    }


    // courses Courses Certificates of Term
    public function getCoursesCertificatesOfTerm(Request $request)
    {
        $term = Term::where('id', $request->term_id)->firstorfail();

        $term_passed_courses = course_test_result::query()->where('user_id',Auth::id())
            ->where('term_id',$term->id)
            ->with(['course.translations'=>function($q){
                $q->where('locale',app()->getLocale());
            }])
            ->get()->map(function ($term_passed_course){
                $term_passed_course->course->translations = $term_passed_course->course->translations->first();
                return $term_passed_course;
            });

        return view('front.content.certificates.term_courses_results',['courses' => $term_passed_courses, 'term' => $term ]);

    }


    // sites cirts
    public function getSitesCertificates(Request $request)
    {
//        $data = $this->getUserSitesTestsResults($request, Auth::guard('web')->user());
//
//        $this->userStatisticsService->setUser(Auth::guard('web')->user());
//        $data['count_tests_in_all_ranges'] = $this->userStatisticsService->getUserCountTestsInAllRanges();
//
//
//        // شهادات زائدة للدبلوم
//        foreach ($data['sites'] as $site){
//            $site->load('extra_certificates');
//            foreach ($site->extra_certificates ?? [] as $extraCertificate){
//              $extraCertificateServicePath = 'App\\Services\\ExtraCertificates\\'.$extraCertificate->alias.'_service';
//              $params = ['site' => $site, 'user' => Auth::guard('web')->user()];
//              $extraCertificateService =  new $extraCertificateServicePath($params);
//              $extraCertificate->result = $extraCertificateService->getResult();
//            }
//        }
//
//        $data['root_sites'] = site::root()->get();
//        foreach ($data['root_sites'] as $site){
//            $site->load('extra_certificates');
//            foreach ($site->extra_certificates ?? [] as $extraCertificate){
//              $extraCertificateServicePath = 'App\\Services\\ExtraCertificates\\'.$extraCertificate->alias.'_service';
//              $params = ['site' => $site, 'user' => Auth::guard('web')->user()];
//              $extraCertificateService =  new $extraCertificateServicePath($params);
//              $extraCertificate->result = $extraCertificateService->getResult();
//            }
//        }



        $data['terms'] = App\Term::query()
            ->withCount('courses')
            ->withCount(['course_term_finished' => function($query) {
                $query->where('user_id', Auth::id());
            }])->with(['term_results'=>function($q){
                $q->where('user_id', Auth::id());
            }])
            ->get()->map(function ($term) {
                $term->term_results = $term->term_results->first();
                return $term;
            });

//       foreach ($data['terms'] as $term){
//           return $term->term_results->rate;
//       }

        return view('front.content.certificates.term_results', compact('data'));
    }

    public function getUserSitesTestsResults($request, $user)
    {
        $lang = App::getLocale() ;
        $id = $user->id ;
        $data = $this->data($request,'my_courses');

        $data['sites'] = $this->userResultsService->setUser($user)->getUserSitesTestsResults();

        return $data;
    }

    public function userSuccessInSite($courses)
    {
        foreach ($courses as $course) {
            if( $this->globalService->siteRateRanges($course->max_degree) < $this->range_degree ){
              return false;
            }
        }
        return true;
    }

    public function certificates_test(Request $request)
    {

        $lang = App::getLocale() ;
        $id = \Auth::guard('web')->user()->id ;
        $data = $this->data($request,'certificates');
        // $data['result'] = App\member::find($id)->test_results()->where('rate','!=',0)->get();
        $data['result'] = member::find($id)->test_results()->where('degree','>',$this->range_degree)->get();
        $data['result'] = DB::table('course_tests_results')->where('user_id',$id)->where('degree','>',$this->range_degree)
                            ->join('courses','courses.id','course_tests_results.course_id')
                            ->join('sites','sites.id','course_tests_results.site_id')
                            ->select('course_tests_results.id','course_tests_results.degree','course_tests_results.rate',
                            'sites.title','courses.title as course_title','courses.id as courses_id')->orderBy('course_tests_results.degree','DESC')->orderBy('sites.title','ASC')->orderBy('course_tests_results.created_at','desc')->get()->groupBy('courses_id');

        return view('front.content.certificates.index_test',$data);
    }

    public function loginWithOutPassword()
    {

        $user_id = session('user_id_to_login');
        if (! $user_id){
          return redirect('/');
        }

        $user = member::withTrashed()->where('id', $user_id)->firstorfail();
        Auth::loginUsingId($user->id);
        session()->forget('user_id_to_login');
        return redirect('/');

    }

    public function certificates_show(Request $request)
    {
        // this function repleced with CertificateController@downloadCertificate
        return redirect()->to('/');
    }

    public function correctEmail(Request $request)
    {
        if (! $request->correct_email){
            return redirect()->back()->with('error','برجاء إدخال البريد الإكتروني');
        }

        $found = member::where('email', $request->correct_email)->exists();
        if ($found){
          return redirect()->back()->with('error','البريد الاكترونى مستخدم من قبل برجاء ادخال بريد الكترونى اخر');
        }

        $user = auth()->user();
        $user->email = $request->correct_email;
        $user->save();

        return redirect()->route('show_verification_email');

    }




    // private function validateCodeExistsActive($code)
    // {
    //     if (! $this->partnerCodeService->isCodeExistsActive($code)){
    //         throw ValidationException::withMessages(['code' => __('messages.invalid_code') ]);
    //     }
    // }
    //
    //
    // private function validateCodeUsed($code)
    // {
    //     if ($this->partnerCodeService->isCodeUsed($code)){
    //         throw ValidationException::withMessages(['code' => __('messages.invalid_code') ]);
    //     }
    // }



}
