<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App;
use App\course;
use App\member;
use App\site;
use App\course_site;
use App\course_test_result;
use App\course_subscription;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Auth;
use App\PrizeUser;
use App\PrizeUserOutside;

use App\PrizeData;
use App\Models\Page;

use App\PrizeMessage;
use Illuminate\Support\Facades\Validator;


use Response;

class PrizeController extends Controller
{

  public function index(Request $request)
  {

        $data['test'] = '';

        // $data['page'] = Page::where('inner_name', 'prizes')->with('activeTranslation')->firstorfail();
        // $htmlPath = $data['page']->activeTranslation->first()->htmlPath();

        $data['data'] = Page::where('inner_name', 'prizes')->where('is_active', 1)->firstorfail();
        $data['translation'] = $data['data']->translation(app()->getLocale())->where('is_active', 1)->firstorfail();
        $htmlPath = $data['translation']->htmlPath();
        $data['html'] = $htmlPath ? file_get_contents($htmlPath) : '';

        $this->seoInfo('prize_page','');

        $data['alreadySubscribed'] = false;
        if ( Auth::check() ){
          $data['alreadySubscribed'] = PrizeUser::where('user_id',Auth::id())->exists();
        }


        return view('front.prizes.index', $data);

  }

  private function getValidCourses()
  {
      return DB::table('courses')
        ->where('status',1)
        ->where('exam_approved',1)
        ->whereNull('deleted_at')
        ->whereNotNull('exam_at')
        ->pluck('id');
  }

  public function subscripe(Request $request)
  {

      if (! Auth::check() ) {
        return redirect()->route('login');
      }

      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:50',
          'email' => 'required|email|string|max:50|unique:members,email,'.Auth::id(),
          'whats_app' => 'required|string|max:20|unique:members,whats_app,'.Auth::id(),
          'notes' => 'nullable|string|max:200',
          'birthday' => 'nullable|date',
          'gender' => 'required|in:0,1,2',
          'country_id' => 'nullable|integer',
          'phone' => 'required|numeric',
      ],
      $messages = [
        'required' => ' :attribute مطلوب',
        'email' => ' :attribute خطاء',
        'max' => ' :attribute يجب الا يزيد عن  :max',
        'unique' => ' :attribute مستخدم من قبل',
        // 'email.required' => 'We need to know your email address!',
      ]);

      if ($validator->fails()) {
          return redirect()->back()->withInput()->withErrors($validator);
      }

      $user = Auth::user();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->birthday = $request->birthday;
      $user->gender = $request->gender;
      $user->country_id = $request->country_id;
      $user->country_id = $request->country_id;
      $user->phone = $request->phone;
      $user->notes = $request->notes;
      $user->save();

      PrizeUser::firstOrCreate(
        ['user_id' => Auth::id()]
      );

      return redirect()->back()->withInput()->with('success', 'تم الاشتراك بنجاح');   ;

  }

  public function add_link_share(Request $request)
  {


      if (! Auth::check() ) {
        return redirect()->route('login');
      }

      $validator = Validator::make($request->all(), [
          'emails' => 'nullable|max:50000|string',
          'whatsapp' => 'nullable|max:50000|string',
          'telegram' => 'nullable|max:50000|string',
          'description' => 'nullable|max:50000|string',
          'links' => 'nullable|max:100000|string',
      ],
      $messages = [
        // 'required' => '  مطلوب',
        'max' => ' :attribute يجب الا يزيد عن  :max',
        // 'unique' => ' :attribute مستخدم من قبل',
        // 'email.required' => 'We need to know your email address!',
      ]);

      if ($validator->fails()) {
          return redirect()->back()->withInput()->withErrors($validator);
      }

      if ( !$request->emails && !$request->email && !$request->email && !$request->email ) {
        return redirect()->back()->withInput()->withErrors(['', 'على الأقل يجب ملاء حقل واحد من الحقول']);
      }


      PrizeData::updateOrCreate(
          [ 'user_id' => auth()->user()->id ],
          [
            'emails' => $request->emails,
            'whatsapp' => $request->whatsapp,
            'telegram' => $request->telegram,
            'description' => $request->description,
            'links' => $request->links
          ]
      );

      return redirect()->back()->withInput()->with('success', 'تم الارسال بنجاح');

  }

  public function indexPublish(Request $request)
  {

      if (! Auth::check() ) {
        return redirect()->route('login');
      }

      $UserData = PrizeData::where('user_id',Auth::id())->first();

      $prizeMessages = PrizeMessage::all();
      return view('front.prizes.index_publish',compact(['prizeMessages','UserData']));

  }

  public function showSubscribeFromOutside(Request $request)
  {


      $validator = Validator::make(['outside' => $request->outside], [
          'outside' => 'required|max:10|string',
      ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


      if (! in_array( $request->outside, $this->getAllowedChanells()) ){
        return redirect()->route('home');
      }

      $course = course::where('link' ,$request->course_id )->first();
      if (! $course) {
        return redirect()->route('home');
      }


      $endDate = date($course->link_ended); //  date("2021-09-29 23:30:0");
      $now = \Carbon\Carbon::now();
      if(  $now->toDateTimeString() > $endDate ) {
        return view('front.prizes.subscribe_out')
        ->with('ended' , ' انتهى وقت التحضير');
      }

      // $registeredBefore = true;
      if ( Auth::check() ){
          // PrizeUserOutside::firstOrCreate([
          //     'user_id' => Auth::id(),
          //     'course_id' => $course->id,
          //     'outside' =>  $request->outside
          //   ]
          // );

          $registeredBefore = PrizeUserOutside::where(['user_id' => Auth::id(), 'course_id' => $course->id, 'outside' =>  $request->outside])->exists();

          $site = course_site::where('course_id',$course->id)->select('site_id')->first(); // ->where('main_site',1)
          $site = site::where('id',$site->site_id ?? null)->select('alias')->first();
          if ($site){
            $courseLink = route('courses.show',['site' => $site->alias,'course' => $course->alias]);
          } else {
            $courseLink = '';
          }

          $courseTitle = $course->title;

          if ($registeredBefore == true){
            $ended = 'تم تسجيل حضورك بنجاح';
          } else {
            $ended = 'اتعهد بحضور ٨٠٪ من الدورة على الأقل';
          }

          // $ended = $ended . ' <a style="font-size: 33px;text-decoration: underline;color: black;" href="' . $courseLink . '" >' . $courseTitle . '</a>';

          // $registeredBefore = false;

          return view('front.prizes.subscribe_out')
            ->with('ended', $ended)
            ->with('course_title', $courseTitle)
            ->with('course_link', $courseLink)
            ->with('registeredBefore', $registeredBefore)
            ;

      }

      session(['come_from_outside' => $request->outside]);
      return redirect()->route('login');

  }

  public function subscribeFromOutside(Request $request)
  {

    $request->merge(['outside' => \Route::input('outside')]);

      $request->validate([
        'agree' => 'accepted',
        'outside' => 'required|max:10|string',
      ],['agree.accepted' => 'برجاء اختيار نص التعهد والضغط على زر "والله على ما أقول شهيد"']);

      if (! in_array( $request->outside, $this->getAllowedChanells()) ){
        return redirect()->route('home');
      }

      $course = course::where('link' ,$request->course_id )->first();
      if (! $course) {
        return redirect()->route('home');
      }

      $endDate = date($course->link_ended); //  date("2021-09-29 23:30:0");
      $now = \Carbon\Carbon::now();
      if(  $now->toDateTimeString() > $endDate ) {
        return view('front.prizes.subscribe_out')
        ->with('ended' , ' انتهى وقت التحضير');
      }

      if ( Auth::check() ){
          PrizeUserOutside::firstOrCreate([
              'user_id' => Auth::id(),
              'course_id' => $course->id,
              'outside' =>  $request->outside
            ]
          );

          $site = course_site::where('course_id',$course->id)->where('main_site',1)->select('site_id')->first();
          $site = site::where('id',$site->site_id ?? null)->select('alias')->first();
          if ($site){
            $courseLink = route('courses.show',['site' => $site->alias,'course' => $course->alias]);
          } else {
            $courseLink = '';
          }

          $courseTitle = $course->title;

          // $ended = 'تم تسجيل حضورك بنجاح <br> إذا بقيت أكثر من 80% من وقت البث، <br> ومهم لتتمة هذا يرجى ترك سؤالك أو تعليقك على  البث المباشر';
          // $ended = $ended . ' في انتظاركم يفتح الاختبار الساعة 11 بإذن الله  ' . '<br>';
          // $ended = $ended . ' <a style="font-size: 33px;text-decoration: underline;color: black;" href="' . $courseLink . '" >' . $courseTitle . '</a>';

          $ended = 'تم تسجيل حضورك بنجاح';
          // $ended = $ended . ' <a style="font-size: 33px;text-decoration: underline;color: black;" href="' . $courseLink . '" >' . $courseTitle . '</a>';

          $registeredBefore = true;

          return view('front.prizes.subscribe_out')
            ->with('ended', $ended)
            ->with('course_title', $courseTitle)
            ->with('course_link', $courseLink)
            ->with('registeredBefore', $registeredBefore)
            ;

      }

      session(['come_from_outside' => $request->outside]);
      return redirect()->route('login');

  }

  private function getAllowedChanells()
  {
    return ['zoom','facebook'];
  }


  // report 1
  public function getUsersByTestsByDegree(Request $request)
  {

        $report = $this->reportUsersByTestsByDegree();

        return view('front.prizes.sta_bytest_bydegree',compact('report'));

  }

  public function reportUsersByTestsByDegree()
  {

        //1-  المستخدمين –
        // المستخدمين –
        // مجموع اشتراكاتهم فى الدورات الفعالة فقط ويستبعد باقى الدورات التى لم تبدأ بعد
        // مجموع اختباراتهم ( اختبار واحد لكل دورة وهو الاختبار الذى به اكبر درجة )
        // متوسط مجموع درجات الاختبارات ( مع الاخذ فى الاعتبار الدرجة الاعلى فى كل اختبار)
        // مرتبين بالاكثر اشتراكا فى الدورات و الاكثر مجموع الدورات :

        $sql = "select user_id, user_name, phone, email, whats_app, count(course_id) as tests_count, sum(max_degree) / count(course_id) as over_all_degree,
                     (
                        select count(course_id) from course_subscriptions WHERE course_subscriptions.user_id = users_tests.user_id
                     ) as all_subscribtions_count,
                     (
                       select count(course_id) from course_subscriptions WHERE course_subscriptions.user_id = users_tests.user_id
                         and course_id in ( select id from courses where status = 1 and exam_approved = 1 and deleted_at is null and exam_at is not null and exam_at <= NOW() )
                     )
                     as subscribtions_count_in_active_courses
                from
                	(
                       SELECT id, user_id, course_id, MAX(degree) as max_degree, (select name from members where id = user_id) as user_name, (select phone from members where id = user_id) as phone, (select email from members where id = user_id) as email, (select whats_app from members where id = user_id) as whats_app
                       FROM `course_tests_results`
                       group by user_id,course_id
                  ) as users_tests
                group by user_id
                order By over_all_degree desc
                limit 100";
        return DB::select($sql); // tests_count desc,



        // 2-  the problem is we have to get all fileds of members to get the tow counts we cant select just id,name from member
        // $data = member::withcount(['test_results','courses'])->get();
        // dd($data->first());

        // 3-  many queries
        // $users = DB::table('members')->orderBy('id')->chunk(20, function($users) use(&$courses,&$acceptedUsers,&$diffs) {
        //     foreach ($users as $user) {
        //       $user->tests_count = DB::table('course_tests_results')->where('user_id',$user->id)->groupBy('course_id')->count();
        //       $user->tests_count = DB::table('course_subscriptions')->where('user_id',$user->id)->count();
        //     }
        // });
        // dd($users);

  }



  // report 2
  public function getUsersTestedInAllHisSubscribtions()
  {
    $report = $this->rebortUsersTestedInAllHisSubscribtions();
    return view('front.prizes.sta_bytested_in_all_his_subscribtions',compact('report'));
  }

  public function rebortUsersTestedInAllHisSubscribtions()
  {
      // the same but where count_tests = count_subscribtion
      $sql = "select user_id, user_name, phone, email, whats_app, count(course_id) as tests_count, sum(max_degree) / count(course_id) as over_all_degree,
                   (
                      select count(course_id) from course_subscriptions WHERE course_subscriptions.user_id = users_tests.user_id
                   ) as all_subscribtions_count,
                   (
                     select count(course_id) from course_subscriptions WHERE course_subscriptions.user_id = users_tests.user_id
                       and course_id in ( select id from courses where status = 1 and exam_approved = 1 and deleted_at is null and exam_at is not null and exam_at <= NOW())
                   )
                   as subscribtions_count_in_active_courses
              from
                (
                     SELECT id, user_id, course_id, MAX(degree) as max_degree, (select name from members where id = user_id) as user_name, (select phone from members where id = user_id) as phone, (select email from members where id = user_id) as email, (select whats_app from members where id = user_id) as whats_app
                     FROM `course_tests_results`
                     group by user_id,course_id
                ) as users_tests
              group by user_id
              having tests_count = subscribtions_count_in_active_courses
              order By over_all_degree desc,tests_count desc
              limit 100";


              // old original
             //   select user_id, user_name, count(course_id) as tests_count, sum(max_degree) / count(course_id) as over_all_degree,
             //   (select count(course_id) from course_subscriptions WHERE course_subscriptions.user_id = users_tests.user_id ) as subscribtions_count
             // from
             // (
             //    SELECT id, user_id, course_id, MAX(degree) as max_degree, (select name from members where id = user_id) as user_name
             //    FROM `course_tests_results`
             //    group by user_id,course_id
             // ) as users_tests
             // group by user_id
             // having tests_count = subscribtions_count
             // order By tests_count desc ,over_all_degree desc;


      return DB::select($sql);

  }



  // report 3
  public function getUsersTestsInEachSite()
  {
    $report = $this->rebortUsersTestsInEachSite();
    return view('front.prizes.sta_bytestes_in_each_site',compact('report'));
  }

  public function rebortUsersTestsInEachSite()
  {

      DB::table('sta')->truncate();

      $sql = "insert into sta
              select
                  members.id,
                  members.name as user_name,
                  members.email,
                  members.whats_app,
                  members.phone,
                  courses.id as cource_id,
                  courses.title as course_title,
                  count(course_id) as tests_count from course_tests_results
              join members on members.id = course_tests_results.user_id
              JOIN courses on courses.id = course_tests_results.course_id

              GROUP by course_tests_results.user_id, course_tests_results.course_id
              ORDER by course_tests_results.user_id";
      DB::insert($sql);


      $sql= "
              SELECT sta.*,
                course_site.course_id,
                course_site.site_id,
                sites.title as site_title ,
                count(course_site.course_id) tests_count_in_site
              FROM `sta`
              JOIN course_site on sta.course_id = course_site.course_id
              JOIN sites on sites.id = course_site.site_id
              GROUP by user_id, course_site.site_id
              ORDER by user_id";
      $report = DB::select($sql);


      $countActiveCoursesInEachSite = collect($this->getCountActiveCoursesInEachSite());
      foreach($report as $item){
        $item->count_active_courses = optional($countActiveCoursesInEachSite->where('id', $item->site_id )->first())->count_active_courses;
      }

      return $report;

  }

  public function getCountActiveCoursesInEachSite()
  {
        $sql = "
            select sites.id, sites.title, count(course_id) as count_active_courses
            from courses
            JOIN course_site on course_site.course_id = courses.id
            JOIN sites on course_site.site_id = sites .id
            WHERE courses.status = 1 and
            courses.exam_approved = 1 and
            courses.deleted_at is null and
            courses.exam_at is not null and
            courses.exam_at < now()
            GROUP by sites.id ORDER by sites.id
          ";
          return DB::select($sql);
  }


}
