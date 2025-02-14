<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\libraries\_commonfn;
use App\libraries\Helpers;
use App\course;

use App\Lesson;

use App\MemberPost;
use App\course_test_result;
use App\Redirect;
use App\Services\CourseService;
use App\Services\TermService;
use App\Services\TermTestResultService;
use App\Services\GlobalService;

use App;
use Auth;
use Session;
use DB;

use App\Traits\ZoomTrait;


class courses extends Controller
{

    use ZoomTrait; // , CacheTrait;

    public function __construct(private CourseService $courseService, private TermService $termService,
      private TermTestResultService $termTestResultService, private GlobalService $globalService)
    {

    }

    public function index(Request $request)
    {

        // get deplome courses
        $site_alias = \Route::input('site');

        // redirect
        if (str_contains($site_alias, '_')){
            $site_alias = str_replace('_','-',$site_alias);
            return redirect()->route('courses.index',$site_alias);
        }

        $data = $this->data($request,'courses');
        $data['title_page'] = __('meta.title.Diplomas');

        $data['site'] = App\site::where(['status' => 1])->whereTranslation('locale', app()->getlocale())->whereTranslation('slug',$site_alias)->select('id')->first();
        if (! $data['site']){
          $redirect = Redirect::site()->where('link', $site_alias)->select('redirect')->firstOrFail();
          return redirect()->route('courses.index', ['site' => $redirect->redirect]);
        }


        $data['site']->increment('views_count');
        $data['result'] = $data['site']->terms()->where('status', 1)->whereTranslation('locale', app()->getlocale())
            ->with(['courses' => function($q){
              return $q->where('status', 1)->whereTranslation('locale', app()->getlocale())
                ->select('courses.id','courses.title','logo','exam_at','exam_approved','courses.new_flag','sort')
                ->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' );
        }])->get();



        $siteCoursesCount = 0; // $data['result']->count();
        foreach($data['result'] as $term){
            $siteCoursesCount = $siteCoursesCount + $term->courses->count();
        };
        $data['siteCoursesCount'] = $siteCoursesCount;
        $this->seoInfo('site', $site_alias, $data['site']);
        $data['title_page']=$data['site']->title;

        $data['subs_count'] = $data['site']->getSubscriptionsCount();

        $data['userFinishedAtLeastOneSite'] = false;
        $data['userCoursesResulte'] = collect([]);

        $siteConditionService = new App\Services\SiteConditionService();

        if (Auth::guard('web')->user()){
            $data['site']->isUserSubscribedInSite = Auth::user()->sites()->where('sites.id',$data['site']->id)->exists();
            $data['site']->userSiteConditionsDetails = $siteConditionService->setSite($data['site'])->setUser(auth()->user())->getUserSiteConditionsDetails();
            $data['userCoursesResulte'] = $this->globalService->getUserCoursesMaxDegrees(Auth::guard('web')->user(), ['siteId' => $data['site']->id]);


            foreach ($data['result'] as $term) {

              $extraTraysService = new App\Services\ExtraTrays([
                'user' => Auth::user(),
                'term_id' => $term->id,
                'locale' => app()->getlocale(),
              ]);
              $extraTrays = $extraTraysService->getUserEmailXtraTrays();
              $extraTrays = $extraTrays < maxTests() ? maxTests() : $extraTrays;

              $userTestsCountOfTerm = $this->termTestResultService->getUserTestsCountOfTerm($term, Auth::user(), app()->getlocale());
              $term->showTermTestToUser = $this->termService->showTermTestToUser($term, Auth::user());
              $term->openTermTestToUser = $this->termService->openTermTestToUser($term, user: Auth::user(),  extraTrays : $extraTrays, userTestsCountOfTerm: $userTestsCountOfTerm);
              $term->userHasTrays = $this->termService->userHasTrays($extraTrays, $userTestsCountOfTerm);
              $term->userResultsOfTerm = collect([]);

              if ($term->showTermTestToUser){
                $term->userResultsOfTerm = $this->termTestResultService->getUserResultsOfTerm($term, Auth::user(), $locale = app()->getlocale());
              }

            }
        }




        foreach ($data['result'] as $term) {
          foreach ($term->courses ?? [] as $course) {
            $courseTranslateFields = $course->translateFields(['courses_translations.date_at']);
            $course->zoom_day_status = $this->courseZoomDayStatus($courseTranslateFields);
          }
        }


        Helpers::storePreviousUrl('site',$data['site']->id);

        $data['successedUsersInEachCountry'] = $this->globalService->getCountSuccessedUsersInEachCountryOfSite([
          'site_id' => $data['site']->id,
          'new_flag' => $data['site']->new_flag
        ]);

        if ($data['site']->new_flag == 0){
            $data['successedUsersInEachCountryDate'] = date('Y-m-d');
        } else {
            $data['successedUsersInEachCountryDate'] = '2022-09-29';
        }

        $data['google_schema_site_items'] = $this->getgoogleSchemaSiteItems($data['site'], $data['result']);


        return view('front.content.courses.index', $data);
    }

    public function show(Request $request)
    {
        // ini_set('memory_limit', '512M');
        $site_alias = \Route::input('site');
        $course_alias = \Route::input('course');

        // redirect
        if (str_contains($site_alias, '_')){
            $site_alias = str_replace('_','-',$site_alias);
            return redirect()->route('courses.show',['site' => $site_alias,'course' => $course_alias]);
        }

        $data = $this->data($request,'courses');

        // $data['site'] = App\site::where(['status' => 1,'alias' => $site_alias])->firstOrFail();
        $data['site'] = App\site::where(['status' => 1])->whereTranslation('locale', app()->getlocale())->whereTranslation('slug',$site_alias)->first();
        if (! $data['site']){
          $site_alias = Redirect::site()->where('link', $site_alias)->select('redirect')->firstOrFail()->redirect;
          return redirect()->route('courses.show', ['site' => $site_alias, 'course' => $course_alias]);
          // $data['site'] = App\site::where(['status' => 1])->whereTranslation('locale', app()->getlocale())->whereTranslation('slug',$site_alias)->firstorfail();
        }

        $data['course'] = $data['site']->courses()->where(['status' => 1])->whereTranslation('alias',($course_alias))->first();
        if (! $data['course']){
          $redirect = Redirect::course()->where('link', $course_alias)->select('redirect')->firstOrFail();
          return redirect()->route('courses.show', ['site' => $site_alias, 'course' => $redirect->redirect]);
        }

        $data['course']->update(['views_count' => $data['course']->views_count + 1 ]);
        $this->seoInfo('course', $data['course']->id, $data['course']);


        // get if user subscribed in this course
        // if user subscribed in this cource get all lessons else get only 3 lesson
        $paginate = 3;
        $subscribed = false;
        if (Auth::guard('web')->user()) {
            $subscribed = Auth::guard('web')->user()->courses()->find($data['course']->id);
            if ( $subscribed ) {
                $paginate = 30;
            }
        }
        $data['subscribed'] = $subscribed;


        $data['lessons'] = Lesson::where('course_id', $data['course']->id)->where(['is_active'=> 1])
          ->whereTranslation('trans_status', 1)->whereNull('deleted_at')
          ->with([
            'options' => function($q) { $q->where('locale', app()->getlocale() );},
            'options.options.option_info'=> function($q) { $q->where('locale', app()->getlocale());},
            'option_values.options.option_info' => function($q) { $q->where('locale', app()->getlocale());} ,
            'option_values.option_value_info' => function($q) { $q->where('locale', app()->getlocale());}
          ])
          ->get();


        $data['title_page']= $data['course']->title;

        Helpers::storePreviousUrl('course',$data['course']->id);

        $data['previousTestsSameCourse'] = null;
        if( Auth::guard('web')->user()){
            // $data['previousTestsSameCourse'] = course_test_result::where('user_id', Auth::id())
            //   ->where('course_id',$data['course']->id)
            //   ->select('id','course_id','degree','created_at')->with('course:id','course.sites')->get();
            $userResultsService = new \App\Services\UserResultsService();
            $data['previousTestsSameCourse'] = $userResultsService->getPreviousTestsSameCourse(Auth::id(), $data['course']->id, app()->getlocale());
        }

        $data['otherCoursesInSite'] = $data['site']->courses()->where('courses.id','!=',$data['course']->id)->whereTranslation('locale', app()->getlocale())->where('courses.status',1)->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get();


        $siteConditionService = new App\Services\SiteConditionService();

        $data['isUserSubscribedInSite'] = false;
        $data['isUserTestedCourse'] = false;
        $data['userFinishedAtLeastOneSite'] = false;

        if (Auth::guard('web')->user()){
            $data['isUserSubscribedInSite'] = Auth::user()->sites()->where('sites.id',$data['site']->id)->exists();
            $data['userTestsCountInCourse'] = $this->globalService->getUserTestsCountInCourse( Auth::guard('web')->user(), $data['course'] );
            $data['isUserTestedCourse'] = $data['userTestsCountInCourse'] > 0 ? true : false;
            $data['site']->isUserSubscribedInSite = Auth::user()->sites()->where('sites.id',$data['site']->id)->exists();
            $data['site']->userSiteConditionsDetails = $siteConditionService->setSite($data['site'])->setUser(auth()->user())->getUserSiteConditionsDetails();
            // $data['site']->siteNotCompleted = $data['site']->isAllExamsOpened();
        }


        // top users in this course
        // $topUsersOfCourse = $this->courseService->getTopUsersOfCourse( $data['course']->id, ['limit' => 5] );
        $courseTestResultsMoreThan = [];
        // if (! empty($topUsersOfCourse)){
        //   $courseTestResultsMoreThan = $this->courseService->getCourseTestResultsMoreThan( $data['course']->id, ['more_than' => Arr::last($topUsersOfCourse)->max_degree ]);
        // }
        $data['courseTestResultsMoreThan'] = $courseTestResultsMoreThan;




        $data['trays'] = maxTests();
        $data['userGetXtraTray'] = false;
        $data['userHasTrays'] = false;
        if (Auth::guard('web')->user()){
            // for all
            $extraTraysService = new App\Services\ExtraTrays([
              'user' => Auth::user(),
              'site_id' =>  $data['site']->id,
              'course_id' => $data['course']->id,
              'locale' => app()->getlocale()
            ]);

            $data['trays'] = $extraTraysService->getUserXtraTrays()['extraTrays'];
            $data['trays'] = $data['trays'] < maxTests() ? maxTests() : $data['trays'];

            $userTrays = Auth::guard('web')->user()->test_results->where('course_id', $data['course']->id )->count();
            $data['userHasTrays'] = $userTrays < $data['trays'];
        }


        return  view('front.content.courses.show',$data);;
    }

    public function showPathCourse(Request $request)
    {
        // ini_set('memory_limit', '512M');
        $site_alias = \Route::input('site');
        $course_alias = \Route::input('course');

        // redirect
        if (str_contains($site_alias, '_')){
            $site_alias = str_replace('_','-',$site_alias);
            return redirect()->route('courses.show',['site' => $site_alias,'course' => $course_alias]);
        }

        $data = $this->data($request,'courses');

        // $data['site'] = App\site::where(['status' => 1,'alias' => $site_alias])->firstOrFail();
        $data['site'] = App\site::where(['status' => 1])->whereTranslation('locale', app()->getlocale())->whereTranslation('slug',$site_alias)->first();
        if (! $data['site']){
            $site_alias = Redirect::site()->where('link', $site_alias)->select('redirect')->firstOrFail()->redirect;
            return redirect()->route('courses.show', ['site' => $site_alias, 'course' => $course_alias]);
            // $data['site'] = App\site::where(['status' => 1])->whereTranslation('locale', app()->getlocale())->whereTranslation('slug',$site_alias)->firstorfail();
        }

        $data['course'] = $data['site']->courses()->where(['status' => 1])->whereTranslation('alias',($course_alias))->first();
        if (! $data['course']){
            $redirect = Redirect::course()->where('link', $course_alias)->select('redirect')->firstOrFail();
            return redirect()->route('courses.show', ['site' => $site_alias, 'course' => $redirect->redirect]);
        }

        $data['course']->update(['views_count' => $data['course']->views_count + 1 ]);
        $this->seoInfo('course', $data['course']->id, $data['course']);



        // get if user subscribed in this course
        // if user subscribed in this cource get all lessons else get only 3 lesson
        $paginate = 3;
        $subscribed = false;
        if (Auth::guard('web')->user()) {
            $subscribed = Auth::guard('web')->user()->courses()->find($data['course']->id);
            if ( $subscribed ) {
                $paginate = 30;
            }
        }
        $data['subscribed'] = $subscribed;


        $data['lessons'] = Lesson::where('course_id', $data['course']->id)->where(['is_active'=> 1])
            ->whereTranslation('trans_status', 1)->whereNull('deleted_at')
            ->with([
                'options' => function($q) { $q->where('locale', app()->getlocale() );},
                'options.options.option_info'=> function($q) { $q->where('locale', app()->getlocale());},
                'option_values.options.option_info' => function($q) { $q->where('locale', app()->getlocale());} ,
                'option_values.option_value_info' => function($q) { $q->where('locale', app()->getlocale());}
            ])
            ->get();


        $data['title_page']= $data['course']->title;

        Helpers::storePreviousUrl('course',$data['course']->id);

        $data['previousTestsSameCourse'] = null;
        if( Auth::guard('web')->user()){
            // $data['previousTestsSameCourse'] = course_test_result::where('user_id', Auth::id())
            //   ->where('course_id',$data['course']->id)
            //   ->select('id','course_id','degree','created_at')->with('course:id','course.sites')->get();
            $userResultsService = new \App\Services\UserResultsService();
            $data['previousTestsSameCourse'] = $userResultsService->getPreviousTestsSameCourse(Auth::id(), $data['course']->id, app()->getlocale());
        }

        $data['otherCoursesInSite'] = $data['site']->courses()->where('courses.id','!=',$data['course']->id)->whereTranslation('locale', app()->getlocale())->where('courses.status',1)->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get();


        $siteConditionService = new App\Services\SiteConditionService();

        $data['isUserSubscribedInSite'] = false;
        $data['isUserTestedCourse'] = false;
        $data['userFinishedAtLeastOneSite'] = false;

        if (Auth::guard('web')->user()){
            $data['isUserSubscribedInSite'] = Auth::user()->sites()->where('sites.id',$data['site']->id)->exists();
            $data['userTestsCountInCourse'] = $this->globalService->getUserTestsCountInCourse( Auth::guard('web')->user(), $data['course'] );
            $data['isUserTestedCourse'] = $data['userTestsCountInCourse'] > 0 ? true : false;
            $data['site']->isUserSubscribedInSite = Auth::user()->sites()->where('sites.id',$data['site']->id)->exists();
            $data['site']->userSiteConditionsDetails = $siteConditionService->setSite($data['site'])->setUser(auth()->user())->getUserSiteConditionsDetails();
            // $data['site']->siteNotCompleted = $data['site']->isAllExamsOpened();
        }


        // top users in this course
        // $topUsersOfCourse = $this->courseService->getTopUsersOfCourse( $data['course']->id, ['limit' => 5] );
        $courseTestResultsMoreThan = [];
        // if (! empty($topUsersOfCourse)){
        //   $courseTestResultsMoreThan = $this->courseService->getCourseTestResultsMoreThan( $data['course']->id, ['more_than' => Arr::last($topUsersOfCourse)->max_degree ]);
        // }
        $data['courseTestResultsMoreThan'] = $courseTestResultsMoreThan;




        $data['trays'] = maxTests();
        $data['userGetXtraTray'] = false;
        $data['userHasTrays'] = false;
        if (Auth::guard('web')->user()){
            // for all
            $extraTraysService = new App\Services\ExtraTrays([
                'user' => Auth::user(),
                'site_id' =>  $data['site']->id,
                'course_id' => $data['course']->id,
                'locale' => app()->getlocale()
            ]);

            $data['trays'] = $extraTraysService->getUserXtraTrays()['extraTrays'];
            $data['trays'] = $data['trays'] < maxTests() ? maxTests() : $data['trays'];

            $userTrays = Auth::guard('web')->user()->test_results->where('course_id', $data['course']->id )->count();
            $data['userHasTrays'] = $userTrays < $data['trays'];
        }


        return  view('front.content.courses.show_path',$data);
    }

    public function getSuccessedUsersOfCountryOfSite(Request $request)
    {

        $data = $this->data($request,'courses');

        // $data['site'] = App\site::where(['status' => 1,'alias' => \Route::input('site')])->select('id','title','alias')->firstOrFail();
        $data['site'] = App\site::where('status' , 1)->whereTranslation('slug' , \Route::input('site'))->select('id','title')->firstOrFail();

        $this->seoInfo('diplom_succeded', $data['site']);

        $country = \Route::input('country');
        $data['currentCountry'] = null;
        if($country){
          $data['currentCountry'] = App\Country::where(['status' => 1,'nicename' => $country])->firstOrFail();
        }

        $data['successedUsersInEachCountry'] = $this->globalService->getCountSuccessedUsersInEachCountryOfSite([
          'site_id' => $data['site']->id,
          'new_flag' => $data['site']->new_flag
        ]);

        if ($data['site']->new_flag == 0){
            $data['successedUsersInEachCountryDate'] = date('Y-m-d');
        } else {
            $data['successedUsersInEachCountryDate'] = '2022-09-29';
        }


        return view('front.content.courses.diplome_successed_users', $data);

    }

    public function renderSuccessedUsersOfCountryOfSite(Request $request)
    {
        // $data = DB::Table('all_results_max')
        //     ->join('members','members.id','all_results_max.user_id')
        //     ->join('countries','members.country_id','countries.id')
        //     ->where('all_results_max.site_id', $request->site_id)
        //     ->where('countries.id', $request->country_id)
        //     ->select('members.name','all_results_max.site_id')
        //     ->orderBy('members.name')
        //     ->paginate(28);

        $site = App\site::where(['id' => $request->site_id, 'status' => 1])->select('new_flag')->firstOrFail();

        $data = DB::Table('member_sites_results')
            ->join('members','members.id','member_sites_results.user_id')
            ->join('countries','members.country_id','countries.id')
            ->where('member_sites_results.site_id', $request->site_id)
            ->where('member_sites_results.user_successed', 1)
            ->where('countries.id', $request->country_id)
            ->select('members.name','member_sites_results.site_id')
            ->orderBy('members.name')
            ->paginate(28);

            return response()->json([
               'fullLoaded' => $data->currentPage() == $data->lastPage(),
               'htmlMore'=> view('front.content.courses.diplome_successed_users_render', ['data' => $data ])->render()
            ]);






        // الاصل هو الناجحين فى الدبلومات
        // if ($site->new_flag == 0){
        //   $data = DB::Table('member_sites_results')
        //       ->join('members','members.id','member_sites_results.user_id')
        //       ->join('countries','members.country_id','countries.id')
        //       ->where('member_sites_results.site_id', $request->site_id)
        //       ->where('member_sites_results.user_successed', 1)
        //       ->where('countries.id', $request->country_id)
        //       ->select('members.name','member_sites_results.site_id')
        //       ->orderBy('members.name')
        //       ->paginate(28);
        //
        //       return response()->json([
        //          'fullLoaded' => $data->currentPage() == $data->lastPage(),
        //          'htmlMore'=> view('front.content.courses.diplome_successed_users_render', ['data' => $data ])->render()
        //       ]);
        // }

        // لكن تم عمل هذا الاستثناء لعرض الذين انجزو الدورات الفعالة من دبلومات المرحلة الثانية
        // حتى لو لم ينتهى الدبلوم وبعد انتهاء الدبلومات سيتم عملها مثل الكويرى فى الاعلى
        // if ($site->new_flag == 1){
        //     $site_id = $request->site_id;
        //     $country_id = $request->country_id;
        //     $data =  DB::Select("
        //         SELECT  members.name, sites.id, count(*) as user_courses_count,
        //             (
        //                 SELECT count(*) from course_site
        //                 JOIN courses on course_site.course_id = courses.id
        //                 where course_site.site_id = member_courses_results.site_id and
        //                 course_site.site_id = $site_id and
        //                 courses.status = 1 and
        //                 courses.exam_approved = 1 and
        //                 courses.deleted_at is null and
        //                 courses.exam_at is not null and
        //                 courses.exam_at <= '2022-09-29 00:00:00'
        //
        //             ) as site_active_courses FROM `member_courses_results`
        //             join sites on sites.id = member_courses_results.site_id and sites.new_flag = 1
        //             JOIN members on members.id = member_courses_results.user_id
        //             JOIN countries on countries.id = members.country_id
        //             WHERE sites.id = $site_id
        //             and countries.id = $country_id
        //             and member_courses_results.test_created_at <= '2022-09-29 00:00:00'
        //             GROUP by user_id,site_id
        //             HAVING site_active_courses = user_courses_count
        //
        //       ");
        //     $data = collect($data);
        //
        //     return response()->json([
        //        'fullLoaded' => true,
        //        'htmlMore'=> view('front.content.courses.diplome_successed_users_render', ['data' => $data ])->render()
        //     ]);
        // }



    }

    public function special()
    {
        $data['sites'] = App\site::where('status' , 1)->get();
        $data['page_name'] = __('meta.title.Diplomas');
        $this->seoInfo('page_inf','الدبلومات');
        return  view('front.content.courses.special',$data);

    }

    public function post(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_alias = \Route::input('course');
        $post_alias = \Route::input('post');

        $data = [] ;
        $data['site'] = App\site::where(['status' => 1,'alias' => $site_alias])->firstOrFail();
        $data['course'] = $data['site']->courses()->where(['status' => 1])->whereTranslation('alias',($course_alias))->firstOrFail();
        if(!$data['course']){

            return redirect('/'.app()->getlocale().'/'.$site_alias,301);

        }


        $data['post'] = Lesson::where(['is_active'=> 1])->where('course_id',$data['course']->id)->whereTranslation('alias',$post_alias)->whereTranslation('trans_status', 1)->first();
        if(!$data['post']){
            return redirect('/'.app()->getlocale(),301);
        }
        $this->seoInfo('post',$data['post']->id);
       if(! Auth::guard('web')->user()->courses()->find($data['course']->id)){

           session()->flash('flashAlerts',[
            'faild' => ['msg'=> __('words.you_not_sub')]
          ]);
           return redirect(route('courses.show',['site' => $data['site']->alias,'course' => $data['course']->alias]));
       }
        // $cach_name = 'posts-'.app()->getLocale().'-'.$site_alias.'-'.$post_alias;
        // if (!Cache::has($cach_name))
        // {
        //      $post = Lesson::where(['is_active'=> 1])->whereTranslation('alias',$post_alias)->whereTranslation('is_active', 1)->firstOrFail();

        //     Cache::forever($cach_name,$post);
        // }
        $data['posts'] =  Lesson::where(['is_active'=> 1])->whereIn('id',$data['course']->post_ids)->whereTranslation('trans_status', 1)->get();

        $data = $data + $this->data($request,'courses',['title' => $data['post']['title'],'keywords' => $data['post']['meta_keywards'],'description' => $data['post']['meta_description']]);
        $data['title_page']=$data['post']->title;


        return view('front.content.courses.post',$data);
    }

    public function memberLessonSeen(Request $request)
    {
        $courseId = $request->course_id;
        $postId = $request->post_id;
        $memberId = Auth::guard('web')->user()->id;

        $seen = MemberPost::firstOrCreate(
            ['course_id' => $courseId , 'post_id' => $postId , 'member_id' => $memberId ],
            ['course_id' => $courseId , 'post_id' => $postId , 'member_id' => $memberId ]
        );

        return response()->json(['data' => 'done']);
    }

    public function getSeen($lessons,$course_id)
    {
        $newPosts = $lessons;
        foreach ($newPosts as $post) {
            $post->seen = MemberPost::where([
                'course_id' => $course_id ,
                'post_id' => $post->id ,
                'member_id' => Auth::guard('web')->user() ? Auth::guard('web')->user()->id : 0
            ])->exists();
        }
        return $newPosts;
    }

    // used in web routes
    public function complete_lesson($lesson_id)
    {
       $user= Auth::guard('web')->user();
        $user->lessons()->attach($lesson_id);
       return redirect()->back();
    }

    // used in web routes
    public function incrementLikes(Request $request)
    {
        $course = course::where('id',$request->id)->first();
        if($course){
            $course->update(['likes_count' => $course->likes_count + 1 ]);
        }
       return response()->json(['likes_count' => $course->likes_count ]);
    }

    public function getgoogleSchemaSiteItems($site, $terms)
    {

        $siteItems = [];

        foreach ($terms as $term) {
          foreach ($term->courses as $key => $course) {
              $siteItems[$key]['@type'] = 'ListItem';
              $siteItems[$key]['@position'] = $key + 1;
              $siteItems[$key]['@item']['@type'] = 'Course';
              $siteItems[$key]['@item']['url'] = route('courses.show',['site' => $site->slug,'course' => $course->alias]);
              $siteItems[$key]['@item']['name'] = $course->title;
              $siteItems[$key]['@item']['description'] = $course->title;
              $siteItems[$key]['@item']['provider']['@type'] = 'Organization';
              $siteItems[$key]['@item']['provider']['name'] = __('core.app_name');
              // $siteItems[$key]['@item']['@provider']['sameAs'] = 'https://www.example.com';
          }
        }

        if (empty($siteItems)) {
            return '';
        }

        $google_schema_site_items = '<script type="application/ld+json">{';
        $google_schema_site_items = $google_schema_site_items . '"@context": "https://schema.org",';
        $google_schema_site_items = $google_schema_site_items . '"@type": "ItemList",';
        $google_schema_site_items = $google_schema_site_items . '"itemListElement":'. json_encode($siteItems, JSON_UNESCAPED_SLASHES);
        $google_schema_site_items = $google_schema_site_items . '}</script>';

        return $google_schema_site_items;

    }

}
