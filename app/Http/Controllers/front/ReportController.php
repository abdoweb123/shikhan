<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App;
use App\course;
use App\member;

use App\site;
use App\siteMap;
use App\language;
use App\LessonOld;
use App\course_test_result;
use App\course_subscription;
use Session;
use Illuminate\Support\Facades\Storage;
use DB;
use File;
use Response;
use App\Services\GlobalService;

class ReportController extends Controller
{

  private $globalService;

  public function __construct(GlobalService $globalService)
  {
      $this->globalService = $globalService;
  }






  public function mainStatistics(Request $request, $lang)
  {

      ini_set('memory_limit', '2048M');

      $request->flash();

      $oldOrNew = (! $request->oldOrNew) ? 0 : 1;

      // $from = $request->from ? ($request->from.' 00:00:00') : date("Y-m-d").' 00:00:00';
      // $to =  $request->to ? ($request->to.' 23:59:59') : date("Y-m-d").' 23:59:59';
      $from = $request->from ? ($request->from.' 00:00:00') : date('Y-m-d',strtotime("-1 days")) . ' 00:00:00';
      $to =  $request->to ? ($request->to.' 23:59:59') : date('Y-m-d',strtotime("-1 days")) . ' 23:59:59';
      $data['from'] = date( 'Y-m-d', strtotime($from) );
      $data['to'] = date( 'Y-m-d', strtotime($to) );




      $oldAndNew = [0,1];
      foreach ($oldAndNew as $oldOrNew) {
            $data = [];

            // opening page without search
            if(! $request->ajax()){
                  // عدد الطلاب


                    // $data['countOfMembers'] = 0;
                    // $data['countOfSites'] = 0;
                    // $data['countOfCourses'] = 0;
                    // $data['countOfActiveCourses'] = 0;
                    // $data['countOfCertficiations'] = 0;
                    // $data['countOfTests'] = 0;
                    // $data['countOfTestedUsers'] = 0;
                    // $data['countOfSubsSites'] = 0;
                    // $data['countOfSubsUsers'] = 0;
                    // $data['countOfNotSubsUser'] = 0;
                    // $data['countOfNotTestedUsers'] = 0;
                    // $data['countOfSubs'] = 0;
                    // $data['persOfTestedUsersToAllUsers'] = 0;
                    // $data['persOfTestedUsersToAllSubs'] = 0;
                    // $data['countSuccessdTestsOfAllSites'] = 0;
                    // $data['countSuccessdUsersOfAllSites'] = 0;
                    // $data['countUsersSuccessedInOneDiplome'] = 0;
                    // $data['countUsersSuccessedMoreThanOneDiplome'] = 0;




                    $data['countOfMembers'] = DB::select("select count(*) as total FROM members")[0]->total;

                    $data['countOfSites']= site::where('new_flag',$oldOrNew)->count(); // new_flag: الدورات الجديدة

                    $data['countOfCourses'] = DB::select("
                      Select count(*) as total from
                        ( select course_site.course_id as total FROM courses
                          join course_site on course_site.course_id = courses.id
                          join sites on sites.id = course_site.site_id
                          where sites.new_flag = $oldOrNew
                          GROUP by course_site.course_id
                        ) courses
                      ")[0]->total;

                    $countOfActiveCourses = DB::select("
                          select courses.id as total FROM courses
                          join course_site on course_site.course_id = courses.id
                          join sites on sites.id = course_site.site_id
                          where courses.status = 1 and
                          courses.deleted_at is null and
                          courses.exam_at < now() and
                          sites.new_flag =$oldOrNew
                          GROUP by course_site.course_id
                        ");
                    $data['countOfActiveCourses'] = count($countOfActiveCourses);

                    //عدد الشهادات
                    $data['countOfCertficiations'] = $this->getCountOfCertficiations($oldOrNew)[0]->total;

                    // عدد الاختبارات
                    $data['countOfTests'] = $this->getCountOfTests($oldOrNew)[0]->total;

                    // عدد المختبرين
                    $data['countOfTestedUsers'] = $this->getCountOfTestedUsers($oldOrNew)[0]->total;

                    // عدد الاشتراكات فى الدبلومات
                    $data['countOfSubsSites'] = DB::select("
                          select count(site_subscriptions.user_id) as total
                          FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id
                          where sites.new_flag = $oldOrNew
                        ")[0]->total;

                    // اجمالى المشتركين بدبلومات
                    $data['countOfSubsUsers'] = DB::select("
                          select count(DISTINCT(site_subscriptions.user_id)) as total
                          FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id
                          where sites.new_flag = $oldOrNew
                        ")[0]->total;



                    // عدد الطلاب الغير مشتركين بدورات
                    $data['countOfNotSubsUser'] = $data['countOfMembers'] - $data['countOfSubsUsers'];

                    // عدد الطلاب الذين لم يختبرو
                    $data['countOfNotTestedUsers'] = $data['countOfSubsUsers'] - $data['countOfTestedUsers'];

                    // عدد الاشتراكات فى الدورات
                    $data['countOfSubs'] = DB::table('course_subscriptions')->count();

                    // نسبة المختبرين للاجمالى
                    $data['persOfTestedUsersToAllUsers'] = round( ($data['countOfTestedUsers'] / $data['countOfMembers']) * 100, 2);

                    // نسبة المسجلين بدورات للاجمالى
                    $data['persOfTestedUsersToAllSubs'] = round( ($data['countOfTestedUsers'] / $data['countOfSubsUsers']) * 100, 2);

                    // عدد الاشخاص الذين اجتازوا دبلوم أو أكثر
                    $data['countSuccessdUsersOfAllSites']  = $this->getCountSuccessdUsersOfAllSites($oldOrNew)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    // عدد الاختبارات الناجحة اى بتكرار الشخص
                    $data['countSuccessdTestsOfAllSites']  = $this->getCountSuccessdTestsOfAllSites($oldOrNew)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    // عدد الاشخاص الذين اجتازو دبلوم واحد فقط
                    $data['countUsersSuccessedInOneDiplome']  = $this->getCountUsersSuccessedInOneDiplome($oldOrNew)[0]->total;
                    // عدد الاشخاص الذين اجتازو اكثر من دبلوم
                    $data['countUsersSuccessedMoreThanOneDiplome']  = $this->getCountUsersSuccessedMoreThanOneDiplome($oldOrNew)[0]->total;

            } else {

                    // search ajax
                    $data['countOfMembersSearch'] = DB::select("select count(*) as total FROM members where created_at >= '$from' and created_at <= '$to' ")[0]->total;

                    // عدد الاختبارات
                    $data['countOfTestsSearch'] =  $this->getCountOfTestsSearch($oldOrNew, $from, $to)[0]->total;

                    // عدد المختبرين
                    $data['countOfTestedUsersSearch'] = $this->getCountOfTestedUsersSearch($oldOrNew, $from, $to)[0]->total;

                    // مختبرين من الجدد
                    // عدد المختبرين من الطلاب الذين سجلو فى هذه الفترة
                    $data['countOfTestedUsersOfRegisteredUsersSearch'] = DB::select("
                      select count(DISTINCT(course_tests_results.user_id)) as total FROM course_tests_results
                      join sites on sites.id = course_tests_results.site_id
                      join members on course_tests_results.user_id = members.id
                      where sites.new_flag = $oldOrNew
                      and members.created_at >= '$from' and members.created_at <= '$to'
                      and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
                    ")[0]->total;


                    $data['countOfSubsSitesSearch'] = DB::select("
                          select count(*) as total FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id
                          where sites.new_flag = $oldOrNew
                          and site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
                      ")[0]->total;

                    // عدد المشتركين بالاعتماد على اشتراكات الدبلومات
                    $data['countOfSubsUsersSearch'] = DB::select("
                          select count(DISTINCT(user_id)) as total FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id
                          where sites.new_flag = $oldOrNew and
                          site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
                      ")[0]->total;

                    // مشتركين من الجدد
                    //  اجمالى المشتركين بدبلومات من الذين سجلو فى هذه الفترة
                    $data['countOfSubsUsersOfRegisteredUsersSearch'] = DB::select("
                          select count(DISTINCT(site_subscriptions.user_id)) as total FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id and sites.new_flag = $oldOrNew
                          where site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
                          and site_subscriptions.user_id in (
                              select id as total FROM members where created_at >= '$from' and created_at <= '$to'
                            )
                      ")[0]->total;
                    // join members on site_subscriptions.user_id = members.id and members.created_at >= '$from' and members.created_at <= '$to'

                     // عدد الطلاب الغير مشتركين بدورات
                    $data['countOfNotSubsUsersSearch'] = $data['countOfMembersSearch'] - $data['countOfSubsUsersSearch'];

                     // عدد الطلاب الذين لم يختبرو
                    $data['countOfNotTestedUsersSearch'] = $data['countOfSubsUsersSearch'] - $data['countOfTestedUsersSearch'];

                     // نسبة المختبرين للاجمالى
                    $data['persOfTestedUsersToAllUsersSearch'] = 0;
                    if ($data['countOfMembersSearch']){
                      $data['persOfTestedUsersToAllUsersSearch'] = round( ($data['countOfTestedUsersSearch'] / $data['countOfMembersSearch']) * 100, 2);
                    }

                     // نسبة المسجلين بدورات للاجمالى
                    $data['persOfTestedUsersToAllSubsSearch'] = 0;
                    if ($data['countOfSubsUsersSearch']){
                      $data['persOfTestedUsersToAllSubsSearch'] = round( ($data['countOfTestedUsersSearch'] / $data['countOfSubsUsersSearch']) * 100, 2);
                    }

                    // عدد الاشخاص الذين اجتازوا دبلوم أو أكثر فى فترة محددة
                    $data['countSuccessdUsersOfAllSitesSearch']  = $this->getcountSuccessdUsersOfAllSitesSearch($oldOrNew, $from, $to)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    // عدد الاختبارات الناجحة اى بتكرار الشخص
                    $data['countSuccessdTestsOfAllSitesSearch']  = $this->getcountSuccessdTestsOfAllSitesSearch($oldOrNew, $from, $to)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    // عدد الاشخاص الذين اجتازو دبلوم واحد فقط
                    $data['countUsersSuccessedInOneDiplomeSearch']  = $this->getCountUsersSuccessedInOneDiplomeSearch($oldOrNew, $from, $to)[0]->total;
                    // عدد الاشخاص الذين اجتازو اكثر من دبلوم فى فترة محددة
                    $data['countUsersSuccessedMoreThanOneDiplomeSearch']  = $this->getCountUsersSuccessedMoreThanOneDiplomeSearch($oldOrNew, $from, $to)[0]->total;


            }


            $dataGroup['oldOrNew_'.$oldOrNew] = $data;

      }


      $dataGroup['from'] = date( 'Y-m-d', strtotime($from) );
      $dataGroup['to'] = date( 'Y-m-d', strtotime($to) );
      // $dataGroup['search'] = false;

      $data['title_page'] = "home";
      $data['old_or_new'] = $oldOrNew;


      if($request->ajax()){
        return response()->json(['data' => $dataGroup ]);
      }


      return view('front.reports_global.main_statistics',$dataGroup );

  }

  // عدد الاختبارات
  public function getCountOfTests($oldOrNew)
  {

      return DB::select("
        select count(*) as total from
          (
            select course_tests_results.id
            FROM course_tests_results
            join sites on sites.id = course_tests_results.site_id
            where sites.new_flag = $oldOrNew
            GROUP by user_id, course_id
          ) a");

  }

  // عدد الاختبارات بحث
  public function getCountOfTestsSearch($oldOrNew, $from, $to)
  {
        return DB::select("
        select count(*) as total from
          (
            select count(*) as total FROM course_tests_results
            join sites on sites.id = course_tests_results.site_id
            where sites.new_flag = $oldOrNew
            and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
            GROUP by user_id, course_id
          ) a ");
  }

  // عدد المختبرين
  public function getCountOfTestedUsers($oldOrNew)
  {
      return  DB::select("
          select count(DISTINCT(user_id)) as total
          FROM course_tests_results
          join sites on sites.id = course_tests_results.site_id
          where sites.new_flag = $oldOrNew
        ");
  }

  // عدد المختبرين بحث
  public function getCountOfTestedUsersSearch($oldOrNew, $from, $to)
  {
      return DB::select("
        select count(DISTINCT(course_tests_results.user_id)) as total FROM course_tests_results
        join sites on sites.id = course_tests_results.site_id
        where sites.new_flag = $oldOrNew
        and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
      ");
  }

  // عدد الشهادات
  public function getCountOfCertficiations($oldOrNew)
  {
       return DB::select("
         select count(id) as total from
           ( select course_tests_results.id from course_tests_results
             join sites on sites.id = course_tests_results.site_id
             where sites.new_flag = $oldOrNew
             and degree >= 50.00
             GROUP by course_tests_results.user_id, course_tests_results.course_id ) count_cirts
         ");
  }

  // عدد الشهادات بحث
  public function getCountOfCertficiationsSearch($oldOrNew, $from, $to)
  {
       return DB::select("
         select count(id) as total from
           ( select course_tests_results.id from course_tests_results
             join sites on sites.id = course_tests_results.site_id
             where sites.new_flag = $oldOrNew
             and degree >= 50.00
             and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
             GROUP by course_tests_results.user_id, course_tests_results.course_id ) count_cirts
         ");
  }

  // عدد الطلاب الذين اجتازو دبلوم او اكثر فى الكل
  public function getCountSuccessdUsersOfAllSites($oldOrNew)
  {

    return DB::select("
          select count(*) as total from
          (
            Select DISTINCT(user_id) FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew
          ) all_successs
        ");
  }

  // عدد الطلاب الذين اجتازو دبلوم او اكثر فى الكل
  // لكن بالتكرار
  // اى عدد الاختبارات الناجحة
  public function getCountSuccessdTestsOfAllSites($oldOrNew)
  {
      return DB::select("
            Select count(*) as total FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew
        ");
  }

  // عدد الاشخاص الذين اجتازة دبلوم او اكثر فى فترة محددة
  public function getcountSuccessdUsersOfAllSitesSearch($oldOrNew, $from, $to)
  {

    return DB::select("
          select count(*) as total from
          (
            Select DISTINCT(user_id) FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew and
            all_results_max.max_created_at >= '$from' and all_results_max.max_created_at <= '$to'
          ) all_successs
        ");
  }

  // عدد الاشخاص الذين اجتازة دبلوم او اكثر فى فترة محددة
  // لكن بالتكرار
  // اى عدد الاختبارات الناجحة
  public function getcountSuccessdTestsOfAllSitesSearch($oldOrNew, $from, $to)
  {

    return DB::select("
            Select count(user_id) as total FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew and
            all_results_max.max_created_at >= '$from' and all_results_max.max_created_at <= '$to'
        ");
  }

  // عدد الاشخاص الذين اجتازو دبلوم واحد فقط
  public function getCountUsersSuccessedInOneDiplome($oldOrNew)
  {
      return DB::select("
          select count(DISTINCT(user_id)) as total from (
            Select user_id, count(user_id) as users_tests_count FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew
            group By(user_id)
            having users_tests_count = 1
          ) a
        ");
  }

  // عدد الاشخاص الذين اجتازو دبلوم واحد فقط فى فترة محددة
  public function getCountUsersSuccessedInOneDiplomeSearch($oldOrNew, $from, $to)
  {
      return DB::select("
          select count(DISTINCT(user_id)) as total from (
            Select user_id, count(user_id) as users_tests_count FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew and
            all_results_max.max_created_at >= '$from' and all_results_max.max_created_at <= '$to'
            group By(user_id)
            having users_tests_count = 1
          ) a
        ");
  }

  // عدد الاشخاص الذين اجتازو اكثر من دبلوم
  public function getCountUsersSuccessedMoreThanOneDiplome($oldOrNew)
  {
      return DB::select("
          select count(DISTINCT(user_id)) as total from (
            Select user_id, count(user_id) as users_tests_count FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew
            group By(user_id)
            having users_tests_count > 1
          ) a
        ");
  }

  //  عدد الاشخاص الذين اجتازو اكثر من دبلوم فى فترة محددة
  public function getCountUsersSuccessedMoreThanOneDiplomeSearch($oldOrNew, $from, $to)
  {
      return DB::select("
          select count(DISTINCT(user_id)) as total from (
            Select user_id, count(user_id) as users_tests_count FROM all_results_max
            join sites on sites.id = all_results_max.site_id
            where sites.new_flag = $oldOrNew and
            all_results_max.max_created_at >= '$from' and all_results_max.max_created_at <= '$to'
            group By(user_id)
            having users_tests_count > 1
          ) a
        ");
  }






  // depend on table site_subscriptions
  public function sitesStatistics(Request $request)
  {


        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 600);

        $request->flash();

        $from = $request->from ? ($request->from.' 00:00:00') : date("Y-m-d").' 00:00:00';
        $to =  $request->to ? ($request->to.' 23:59:59') : date("Y-m-d").' 23:59:59';
        $data['from'] = date( 'Y-m-d', strtotime($from) );
        $data['to'] = date( 'Y-m-d', strtotime($to) );

        $data['sites'] = DB::select("
          SELECT sites.id, sites.title, new_flag,
          	(
              select COUNT(*) from site_subscriptions
          		where site_subscriptions.site_id = sites.id
          	) subs_count ,
            (
              select COUNT(*) from site_subscriptions
          		where site_subscriptions.site_id = sites.id
              and site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
          	) subs_count_search ,
            (
              select COUNT( DISTINCT(user_id) ) from site_subscriptions
              where site_subscriptions.site_id = sites.id
          	) subs_users_count ,
            (
              select COUNT( DISTINCT(user_id) ) from site_subscriptions
              where site_subscriptions.site_id = sites.id
              and site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
          	) subs_users_count_search ,
            (
            	SELECT COUNT(*) from course_tests_results
        		  join course_site on course_tests_results.course_id = course_site.course_id
        		  where course_site.site_id = sites.id
        		  GROUP by course_site.site_id
            ) tests_count ,
            (
            	SELECT COUNT(*) from course_tests_results
        		  join course_site on course_tests_results.course_id = course_site.course_id
        		  where course_site.site_id = sites.id
              and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
        		  GROUP by course_site.site_id
            ) tests_count_search ,
            (
            	SELECT COUNT(DISTINCT(user_id)) from course_tests_results
        		  join course_site on course_tests_results.course_id = course_site.course_id
        		  where course_site.site_id = sites.id
        		  GROUP by course_site.site_id
            ) tested_users ,
            (
            	SELECT COUNT(DISTINCT(user_id)) from course_tests_results
        		  join course_site on course_tests_results.course_id = course_site.course_id
        		  where course_site.site_id = sites.id
              and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
        		  GROUP by course_site.site_id
            ) tested_users_search ,
            (
            	SELECT COUNT(course_id)	FROM `course_site`
        		  JOIN courses on courses.id = course_site.course_id
        		  where course_site.site_id = sites.id
        		  GROUP by course_site.site_id
            ) courses_count ,
            (
              Select count(*) FROM all_results_max WHERE site_id = sites.id
            ) successedUsers,
            (
              Select count(*) FROM all_results_max WHERE site_id = sites.id and max_created_at >= '$from' and max_created_at <= '$to'
            ) successedUsersSearch,
            (
            	SELECT COUNT(course_id)
        		  FROM `course_site`
        		  JOIN courses on courses.id = course_site.course_id
        		  where course_site.site_id = sites.id

        		  and courses.status = 1 and
                  courses.exam_approved = 1 and
                  courses.deleted_at is null and
                  courses.exam_at < now()

        		  GROUP by course_site.site_id
            ) active_courses_count
        FROM sites order By new_flag, sites.id
        ");



        foreach ($data['sites'] as $site) {
            $site->cirtsCount = 0;

            if ($site->courses_count){ // my be no courses in this site
                $site->cirtsCount = DB::select("select count(id) as total from
                  ( select course_tests_results.id from course_tests_results
                      JOIN course_site on course_tests_results.course_id = course_site.course_id
      			          where course_site.site_id = $site->id
                      and degree >= 50.00 GROUP by course_tests_results.user_id, course_tests_results.course_id
                  ) count_cirts")[0]->total;

                $site->cirtsCountSearch = DB::select("select count(id) as total from
                  ( select course_tests_results.id from course_tests_results
                      JOIN course_site on course_tests_results.course_id = course_site.course_id
      			          where course_site.site_id = $site->id
                      and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
                      and degree >= 50.00 GROUP by course_tests_results.user_id, course_tests_results.course_id
                  ) count_cirts")[0]->total;
            }
        }



        $data['counts'] = collect($data['sites'])->groupBy('new_flag')->map(function ($item, $key) use($from, $to) {
            $oldOrNew = $item->first()->new_flag;

            $subs_count_total = 0;
            $subs_count_total_search = 0;
            foreach ($item as $child) {
                $subs_count_total = $subs_count_total + ($child->subs_count * $child->courses_count);
                $subs_count_total_search = $subs_count_total_search + ($child->subs_count_search * $child->courses_count);
            }
            $item->subs_count_total = $subs_count_total;
            $item->subs_count_total_search = $subs_count_total_search;

            $item->subs_users_count_total = $item->sum('subs_users_count');
            $item->subs_users_count_total_search = $item->sum('subs_users_count_search');

            $item->tests_count_total = $item->sum('tests_count');
            $item->tests_count_total_no_dublicate = $this->getCountOfTests($oldOrNew)[0]->total;
            $item->tests_count_total_search = $item->sum('tests_count_search');
            $item->tests_count_total_search_no_dublicate = $this->getCountOfTestsSearch($oldOrNew, $from, $to)[0]->total;


            $item->tested_users_total = $item->sum('tested_users');
            $item->tested_users_total_no_dublicate = $this->getCountOfTestedUsers($oldOrNew)[0]->total;
            $item->tested_users_total_search = $item->sum('tested_users_search');
            $item->tested_users_total_search_no_dublicate = $this->getCountOfTestedUsersSearch($oldOrNew, $from, $to)[0]->total;


            $item->courses_count_total = $item->sum('courses_count');

            $item->successedUsers_total = $item->sum('successedUsers');
            $item->successedUsers_total_no_dublicate = 0; // $this->getCountSuccessdUsersOfAllSites($oldOrNew)[0]->total;
            $item->successedUsers_total_search = $item->sum('successedUsersSearch');
            $item->successedUsers_total_search_no_dublicate = 0; // $this->getcountSuccessdUsersOfAllSitesSearch($oldOrNew, $from, $to)[0]->total;

            $item->active_courses_count_total = $item->sum('active_courses_count');

            $item->cirtsCount_total = $item->sum('cirtsCount');
            $item->cirtsCount_total_no_dublicate = $this->getCountOfCertficiations($oldOrNew)[0]->total;
            $item->cirtsCount_total_search = $item->sum('cirtsCountSearch');
            $item->cirtsCount_total_search_no_dublicate = $this->getCountOfCertficiationsSearch($oldOrNew, $from, $to)[0]->total;



            return $item;
        });

        return view('front.reports_global.sites_statistics', $data );

  }


















  public function advanced(Request $request, $lang)
  {

      if($request->all() != null ){
        $data['from']=$request->from;
        $data['to']=$request->to;
        $data['from2']=$request->from2;
        $data['to2']=$request->to2;

        $data['members_count_for_search']=member::whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->count();
        $data['course_tests_count_for_search']=course_test_result::whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->select('user_id')->groupBy('user_id')->get()->count();
        $data['all_course_subscription_count_for_search']=DB::table('course_subscriptions')->distinct('user_id')
                                                 ->whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->count();
        $data['all_course_subscription_countRepeat_for_search']=DB::table('course_subscriptions')
                                                    ->whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->count();
                                                    /// 2
        $data['members_count_for_search2']=member::whereDate('created_at','>=',$request->from2)->whereDate('created_at','<=',$request->to2)->count();
        $data['course_tests_count_for_search2']=course_test_result::whereDate('created_at','>=',$request->from2)->whereDate('created_at','<=',$request->to2)->select('user_id')->groupBy('user_id')->get()->count();
        $data['all_course_subscription_count_for_search2']=DB::table('course_subscriptions')->distinct('user_id')
                                               ->whereDate('created_at','>=',$request->from2)->whereDate('created_at','<=',$request->to2)->count();
        $data['all_course_subscription_countRepeat_for_search2']=DB::table('course_subscriptions')
                                                            ->whereDate('created_at','>=',$request->from2)->whereDate('created_at','<=',$request->to2)->count();
      // return   $data;
      }else{
      }

      $data['members_count']=member::count();
      $data['course_tests_count']=course_test_result::select('user_id')->groupBy('user_id')->get()->count();
      $data['all_course_subscription_count']=DB::table('course_subscriptions')
                                               ->distinct('user_id')
                                               ->count();
     $data['title_page'] = "home";
      $data['all_course_subscription_countRepeat']=DB::table('course_subscriptions')
                                                        ->count();
      // dd($data['dataWithCount'] );
      return view('front.b_report_advanced',$data );

  }

  public function ajax_courses(Request $request, $lang)
  {

    $data = site::where('id',$request->option)->first()->courses()->select('courses.id','courses.title')->get();
    // dd($data);
    return $data;

  }

  public function users_report(Request $request, $lang,$alias)
  {

     $data['course'] = course::whereTranslation('alias',$alias)->first();

     $id_testing=$data['course']->test_results->pluck('user_id');

     $data['Notsubscribers']=$data['course']->subscribers->whereNotIn('id',$id_testing);
     return view('front.b_report_getUser',$data);

  }

  public function b_report_courses_register(Request $request, $lang)
  {

      ini_set('memory_limit', '512M');

      if ($request->type!=4){
          $data['members'] = member::select('id','name','whats_app','phone','email','created_at','status')
            ->withCount(['courses'=> function($query) {
                $query->where('exam_at' ,'<=', date('Y-m-d') )->where('exam_at','!=', Null)->whereNull('deleted_at')->where('status',1);
            }])->get();
          foreach ($data['members'] as $key => $member) {
              $member->test_results_count = course_test_result::where('user_id',$member->id)->groupBy('course_id')->count();
          }
      }

      if ($request->type == 1) {
              $data['members'] = $data['members']->where('courses_count',0);
      } elseif ($request->type == 2) {
              $data['members'] = $data['members']->where('courses_count','>',0)->where('test_results_count',0);
      } elseif($request->type == 3) {
              $data['members'] =$data['members']->where('courses_count','>',0)->where('test_results_count','>',0);
              foreach ($data['members'] as $key => $value) {
                $value->ev_courses_of_test = round($value->test_results_count/$value->courses_count*100,2);
              }
              $data['members']=$data['members']->where('ev_courses_of_test','<',50);
      } elseif ($request->type == 4) {
              $data['members'] = member::select('id','name','whats_app','phone','email','created_at','status')
                ->withCount(['courses'=> function($query) {
                  $query->whereDate('exam_at' ,'<=', date('Y-m-d H:i') )->whereNotNull('exam_at')->whereNull('deleted_at')->where('status',1);
                }])->paginate(100); // ->has('prizes')  means ان المستخدمين قامو بتحديث بياناتهم
              foreach ($data['members'] as $key => $member) {
                  $member->test_results_count = course_test_result::where('user_id',$member->id)->groupBy('course_id')->select('course_id')->get()->count();
                  $member->avg_degree = $member->courses_count ? round($member->test_results_count/$member->courses_count*100,2) : 0;
              }
              $courses_count = course::whereNotNull('exam_at')->whereDate('exam_at' ,'<=', date('Y-m-d H:i') )->whereNull('deleted_at')->where('status',1)->count();
              $test_results_count = $courses_count - 4;
              $data['members'] = $data['members']->where('courses_count',$courses_count)->where('test_results_count','>=',$test_results_count);
    } else {
              dd($data['members']);
    }

    $data['count'] = $data['members']->count();
    $data['type'] = $request->type;

     return view('front.b_report_register_advanced',$data);

  }


  public function getWithRepeat($courses , $request)
  {
    $site=site::whereNotNull('id');
    if( $request->site_id != null ){
      $courses=$site->where('id',$request->site_id)->first()->courses();
    }else{
      $courses=course::whereNotNull('id');
    }

    if($request->course_id != null ){
      $courses=$courses->where('courses.id',$request->course_id);
    }else{
      $data['is_not_course']=1;
    }

    if($request->has('from') && $request->has('to')){
         $courses=$courses->withCount(['test_results' => function($query) use ($request) {
            $query->whereDate('course_tests_results.created_at','>=',$request->from)->whereDate('course_tests_results.created_at','<=',$request->to);
         }, 'subscribers' => function($query) use ($request) {
            $query->whereDate('course_subscriptions.created_at','>=',$request->from)->whereDate('course_subscriptions.created_at','<=',$request->to);
        }]);
    } elseif ($request->has('from') && !$request->has('to')) {
        $courses=$courses->withCount(['test_results' => function($query) use ($request) {
            $query->whereDate('course_tests_results.created_at','>=',$request->from);
        }, 'subscribers' => function($query) use ($request) {
            $query->whereDate('course_subscriptions.created_at','>=',$request->from);
        }]);
    }  elseif (!$request->has('from') && $request->has('to')) {
        $courses=$courses->withCount(['test_results' => function($query)  use ($request){
            $query->whereDate('course_tests_results.created_at','<=',$request->to);
        }, 'subscribers' => function($query) use ($request){
            $query->whereDate('course_subscriptions.created_at','<=',$request->to);
        }]);
    } else {
        $courses=$courses->withCount(['test_results', 'subscribers']);
    }

    // dd($courses);
    $data['resultsWithCount']=$courses->get();

    return $data['resultsWithCount'];
  }








}
