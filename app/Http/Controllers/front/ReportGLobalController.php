<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\member;
use App\Services\GlobalService;
use App\site;

class ReportGLobalController extends Controller
{

  private $globalService;
  public function __construct( GlobalService $globalService )
  {
      $this->globalService = $globalService;
  }



  // report 1
  public function getUsersByTestsByDegree(Request $request)
  {
        $report = $this->reportUsersByTestsByDegree();
        return view('front.reports_global.sta_bytest_bydegree',compact('report'));
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
                       SELECT course_tests_results.id, user_id, course_tests_results.course_id, MAX(degree) as max_degree, (select name from members where id = user_id) as user_name, (select phone from members where id = user_id) as phone, (select email from members where id = user_id) as email, (select whats_app from members where id = user_id) as whats_app
                       FROM `course_tests_results`
                       group by user_id,course_id
                  ) as users_tests
                group by user_id
                order By over_all_degree desc
                "; // limit 100
        return DB::select($sql); // tests_count desc,


        // join course_site on course_site.course_id = course_tests_results.course_id
        // ,course_site.site_id




        // بطريقة اخر نفس لنتيجة
        // $sql = "
        // select id as user_id, name as user_name, phone, email, whats_app, count(course_id) as tests_count, sum(max_degree) / count(course_id) as over_all_degree,all_subscribtions_count,subscribtions_count_in_active_courses
        // from
        // (
        //     select members.id,name,email,whats_app,phone,course_tests_results.course_id,MAX(degree) as max_degree,
        //         (
        //             select count(course_id) from course_subscriptions WHERE course_subscriptions.user_id = members.id
        //         ) as all_subscribtions_count,
        //         (
        //             select count(course_id) from course_subscriptions WHERE course_subscriptions.user_id = members.id
        //             and course_id in ( select id from courses where status = 1 and exam_approved = 1 and deleted_at is null and exam_at is not null and
        //             exam_at <= NOW() )
        //         ) as subscribtions_count_in_active_courses
        //     from members
        //     join course_tests_results on members.id = course_tests_results.user_id
        //     group by course_tests_results.user_id,course_tests_results.course_id
        // ) as users_tests
        //
        // group by id
        // order By over_all_degree desc";
        // return DB::select($sql);

  }





  // // report 1- سجلو فى جميع الدورات وباقى لهم دورة أو إثنين أو ثلاث أو أربعه لم يختبروا فيها
  // report الطلاب الذين باقى لهم 5 دورات من الدورات الفعالة لم يختبروا فيها وتم الغاء شرط ان يكونو سجلو ام لا
  public function getUsersRegisterdInAllCoursesAndLessXCoursesNotTestedByDegree(Request $request)
  {

        $countActiveCourses = count($this->getActiveCourses());

        $report = $this->reportUsersByTestsByDegree();
        $report = collect($report)->filter(function ($item) use ($countActiveCourses){
            // if( $item->subscribtions_count_in_active_courses < $countActiveCourses  ) {  // يجب ان يكون اشتراكاته اكبر من او تساوى عدد الدورات الفعالة على الموقع
            //   return false;
            // }
            // if( $countActiveCourses - $item->tests_count > 5 ) { // يجب ان يكون اختباراته اقل من عدد الدورات الفعالة على الموقع ب 5
            //   return false;
            // }
            if ( $item->tests_count < 23 ) { // يجب ان يكون اختباراته اكبر من 23 اختبار
              return false;
            }
            return true;
        })->values()->sortby('over_all_degree');

        return view('front.reports_global.sta_registerd_in_all_courses_and_less_x_courses_not_tested_by_degree',compact(['report','countActiveCourses']));
  }


  public function getUsersRegisterdInAllCourses(Request $request)
  {
        // الطلاب الذين اختبرو
        $countActiveCourses = count($this->getActiveCourses());
        $report = $this->reportUsersByTestsByDegree();
        return view('front.reports_global.sta_registerd_in_all_courses',compact(['report','countActiveCourses']));
  }


  // report 2
  public function getUsersTestedInAllHisSubscribtions()
  {
    $report = $this->rebortUsersTestedInAllHisSubscribtions();
    return view('front.reports_global.sta_bytested_in_all_his_subscribtions',compact('report'));
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
              ";

      return DB::select($sql);

  }





  // report 3
  public function getUsersTestsInEachSite()
  {
    $report = $this->rebortUsersTestsInEachSite();
    return view('front.reports_global.sta_bytestes_in_each_site',compact('report'));
  }

  public function rebortUsersTestsInEachSite()
  {

    ini_set('memory_limit', '512M');
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
                  count(course_id) as tests_count
                  from course_tests_results
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



  // اشتراكات الطلاب داخل كل دبلوم
  public function getUsersSubscriptionsInEachSite()
  {
    $report = $this->rebortUsersSubscriptionsInEachSite();
    $countCoursesInEachSite = count($this->getCountCoursesInEachSite());

    return view('front.reports_global.sta_users_subscriptions_in_each_site',compact(['report','countCoursesInEachSite']));
  }

  public function rebortUsersSubscriptionsInEachSite()
  {

    $sql = "Select members.id, members.name as user_name, sites.title as site_title, members.email, members.phone, members.whats_app,
                  course_site.site_id, count(course_subscriptions.course_id) as tests_subscriptions_in_site
            FROM members
            JOIN course_subscriptions on members.id = course_subscriptions.user_id
            JOIN course_site on course_site.course_id = course_subscriptions.course_id
            JOIN sites on sites.id = course_site.site_id
            GROUP by course_subscriptions.user_id,course_site.site_id
            ORDER by user_id asc";

      return DB::select($sql);

  }






  // ترتيب الطلاب بمعدل الدرجات على مستوى الدبلوم
  public function getUsersByDegreeInEachSite()
  {
    $report = $this->rebortUsersByDegreeInEachSite();
    return view('front.reports_global.sta_users_by_degree_in_each_site',compact(['report']));
  }

  public function rebortUsersByDegreeInEachSite()
  {
      ini_set('memory_limit', '512M');
      $sql = "select id as user_id, name as user_name, phone, email, whats_app, site_id, title as site_title, count(course_id) user_courses_count_in_site, sum(max_degree) / count(site_id) as over_all_degree
              from
                (
                   SELECT members.id, members.name, members.phone , members.email, members.whats_app,
                      course_site.site_id, course_site.course_id, MAX(degree) as max_degree, sites.title
                   FROM `course_tests_results`
                   Join course_site on course_tests_results.course_id = course_site.course_id
                   Join members on members.id = course_tests_results.user_id
                   Join sites on sites.id = course_site.site_id
                   group by user_id, course_tests_results.course_id, course_site.site_id
                   HAVING max_degree > '50:00'
                ) as users_tests
              group by user_id,site_id
              order By over_all_degree desc,user_id
              "; //
      return DB::select($sql); // tests_count desc,
  }


  public function getAllSites()
  {
      return site::all();
  }

  public function getActiveCourses()
  {
    // join course_site on courses.id = course_site.course_id
        $sql = "
            select * from courses
            WHERE courses.status = 1 and
            courses.exam_approved = 1 and
            courses.deleted_at is null and
            courses.exam_at is not null and
            courses.exam_at < now()
          ";
          return DB::select($sql);
  }


  public function getCountCoursesInEachSite()
  {
        $sql = "
            select sites.id, sites.title, count(course_id) as count_courses
            from courses
            JOIN course_site on course_site.course_id = courses.id
            JOIN sites on course_site.site_id = sites .id
            GROUP by sites.id ORDER by sites.id
          ";
          return DB::select($sql);
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



  public function getUserDetails(Request $request)
  {

  }


  public function rptTest()
  {

  }

  // تقرير تسجيل الحضور من الخارج - زووم
  public function getUsersRegisteredFromExtrnal()
  {

      $registeredFromExtrnal = DB::Table('prizes_users_outside')
        ->join('members','members.id','prizes_users_outside.user_id')
        ->join('courses','courses.id','prizes_users_outside.course_id')
        ->select('members.id','members.name', 'members.email','members.phone','members.whats_app',
          'courses.title','prizes_users_outside.outside')
        ->get()->groupBy('id');


        foreach ($registeredFromExtrnal as $user) {
            $user->first()->overAllDegree = $this->globalService->getUserOverAllDegree($user->first())[0]->over_all_degree;
        }

      return view('front.reports_global.sta_registered_from_extrnal',compact('registeredFromExtrnal'));

  }


  // الجوائز الثانية - كل من اكمل دبلوم مرتبين بمتوسط الدرجة على مستوى الدبلوم
  public function getUsersCompleteDiplome()
  {
    $report = $this->rebortUsersCompleteDiplome();
    $countActiveCoursesInEachSite = $this->getCountActiveCoursesInEachSite();

    return view('front.reports_global.sta_users_complete_diplome',compact(['report','countActiveCoursesInEachSite']));
  }
  public function rebortUsersCompleteDiplome()
  {
      ini_set('memory_limit', '512M');

      $sql = "select 	user_id, user_name,email, phone, whats_app, site_id, site_title, count(course_id) as user_courses_count_in_site,
                sum(max_degree) / count(course_id) as over_all_degree
              from
              	(
                	SELECT course_tests_results.id, user_id, course_site.site_id, sites.title as site_title,
                        	course_tests_results.course_id, MAX(degree) as max_degree ,
                	members.name as user_name, members.email, members.phone, members.whats_app

                    FROM `course_tests_results`
                    join course_site on course_site.course_id = course_tests_results.course_id
                    Join members on members.id = course_tests_results.user_id
                    Join sites on sites.id = course_site.site_id
                    group by course_tests_results.user_id, course_site.site_id, course_tests_results.course_id
                 ) as users_tests
              GROUP by user_id, site_id
              ORDER BY user_courses_count_in_site desc, over_all_degree desc

          "; // having count_tests_in_diplome > 5    limit 500
      return DB::select($sql);
  }


}
