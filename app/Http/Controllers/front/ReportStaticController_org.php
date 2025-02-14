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

class ReportStaticController_org extends Controller
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

                    $data['countOfSites']= site::where('new_flag',$oldOrNew)->count();


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
                          sites.new_flag = $oldOrNew
                          GROUP by course_site.course_id
                        ");
                    $data['countOfActiveCourses'] = count($countOfActiveCourses);

                    //عدد الشهادات
                    $data['countOfCertficiations'] = $this->getCountOfCertficiations($oldOrNew)[0]->total;

                    // عدد الاختبارات
                    $data['countOfTests'] = $this->getCountOfTests($oldOrNew)[0]->total;

                    // عدد المختبرين
                    $data['countOfTestedUsers'] = $this->getCountOfTestedUsers($oldOrNew)[0]->total;


                    // اجمالى الاشتراكات فى المرحلة
                    $data['countOfSubsSites'] = DB::select("
                          select count(*) as total
                          FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id
                          where sites.new_flag = $oldOrNew
                        ")[0]->total;

                    // لحمالى المشتركين فى المرحلة - بدون تكرار
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




                    // عدد الاشخاص الذين اجتازوا دبلوم أو أكثر - عدد الناجحين
                    $data['countSuccessdUsersOfAllSites']  = $this->getCountSuccessdUsersOfAllSites($oldOrNew)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام

                    // عدد الاختبارات الناجحة اى بتكرار الشخص
                    // عدد الدبلومات التى تم اجتيازاها بتكرار الدبلومات
                    $data['countSuccessdTestsOfAllSites']  = $this->getCountSuccessdTestsOfAllSites($oldOrNew)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام

                    // عدد الاشخاص الذين اجتازو دبلوم واحد فقط
                    $data['countUsersSuccessedInOneDiplome']  = $this->getCountUsersSuccessedInOneDiplome($oldOrNew)[0]->total;

                    // عدد الاشخاص الذين اجتازو اكثر من دبلوم
                    $data['countUsersSuccessedMoreThanOneDiplome'] = $this->getCountUsersSuccessedMoreThanOneDiplome($oldOrNew)[0]->total;

            } else {
                    // search ajax

                    // عدد الطلاب الذين اشتركو فى فترة معين
                    $data['countOfMembersSearch'] = DB::select("select count(*) as total FROM members where created_at >= '$from' and created_at <= '$to' ")[0]->total;

                    //  عدد الاختبارات فى المرحلة فى فترة
                    $data['countOfTestsSearch'] = $this->getCountOfTestsSearch($oldOrNew, $from, $to)[0]->total;

                    // عدد المختبرين فى المرحلة فى فترة
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


                    //  اجمالى الاشتراكات فى المرحلة فى فترة
                    $data['countOfSubsSitesSearch'] = DB::select("
                          select count(*) as total FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id
                          where sites.new_flag = $oldOrNew
                          and site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
                      ")[0]->total;


                    //  اجمالى المشتركين فى مرحلة فى فترة
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
      // good performance than DISTINCT
      return DB::select("
        select count(*) as total from
         (
            select member_courses_results.id
            FROM member_courses_results
            where member_courses_results.site_new_flag = $oldOrNew
            GROUP by user_id, course_id
          ) a
        ");

        // طريقة اخرى
        // الاختبار بدون تكرار نفس العدد id
        // SELECT count(DISTINCT test_id) FROM `member_courses_results` where member_courses_results.site_new_flag = 0

  }

  // عدد الاختبارات بحث
  public function getCountOfTestsSearch($oldOrNew, $from, $to)
  {

        return DB::select("
          select count(*) as total from
            (
              select count(*) as total FROM member_courses_results
              where member_courses_results.test_created_at >= '$from' and member_courses_results.test_created_at <= '$to'
              and member_courses_results.site_new_flag  = $oldOrNew
              GROUP by user_id, course_id
            ) a ");

  }

  // عدد المختبرين
  public function getCountOfTestedUsers($oldOrNew)
  {

        return  DB::select("
          SELECT count(DISTINCT(user_id)) as total
          FROM `member_courses_results`
          where member_courses_results.site_new_flag = $oldOrNew
        ");

  }

  // عدد المختبرين بحث
  public function getCountOfTestedUsersSearch($oldOrNew, $from, $to)
  {

      return DB::select("
        select count(DISTINCT(user_id)) as total FROM member_courses_results
        where member_courses_results.test_created_at >= '$from' and member_courses_results.test_created_at <= '$to' and
        member_courses_results.site_new_flag = $oldOrNew
      ");

  }

  // عدد الشهادات
  public function getCountOfCertficiations($oldOrNew)
  {

       // old bad performance
       return DB::select("
         select count(id) as total from
           ( select member_courses_results.id from member_courses_results
             where test_degree >= 50.00 and member_courses_results.site_new_flag  = $oldOrNew
             GROUP by member_courses_results.user_id, member_courses_results.course_id
            ) count_cirts
         ");

       // good performance
       // return DB::select("select count(DISTINCT user_id,course_id ) as total
       //    from member_courses_results where test_degree >= 50.00 and member_courses_results.site_new_flag = $oldOrNew");

  }

  // عدد الشهادات بحث
  public function getCountOfCertficiationsSearch($oldOrNew, $from, $to)
  {

        // هتبقى زى اللى قبلها
       return DB::select("
         select count(id) as total from
           ( select member_courses_results.id from member_courses_results
             where member_courses_results.test_created_at >= '$from' and member_courses_results.test_created_at <= '$to' and
             member_courses_results.site_new_flag = $oldOrNew and test_degree >= 50.00
             GROUP by member_courses_results.user_id, member_courses_results.course_id ) count_cirts
         ");

  }

  // عدد الطلاب الذين اجتازو دبلوم او اكثر فى الكل
  public function getCountSuccessdUsersOfAllSites($oldOrNew)
  {
      // good performance
      return DB::select("
            SELECT count(DISTINCT(user_id)) as total FROM member_sites_results
            WHERE user_successed = 1 and member_sites_results.site_new_flag = $oldOrNew
      ");

  }

  // عدد الطلاب الذين اجتازو دبلوم او اكثر فى الكل
  // لكن بالتكرار
  // اى عدد الاختبارات الناجحة
  public function getCountSuccessdTestsOfAllSites($oldOrNew)
  {
      // good performance
      return DB::select("
          SELECT count(*) as total FROM `member_sites_results`
          WHERE user_successed = 1 and member_sites_results.site_new_flag = $oldOrNew
      ");

  }

  // عدد الاشخاص الذين اجتازة دبلوم او اكثر فى فترة محددة
  public function getcountSuccessdUsersOfAllSitesSearch($oldOrNew, $from, $to)
  {

      return DB::select("
            SELECT count(DISTINCT(user_id)) as total FROM member_sites_results
            WHERE user_successed = 1 and member_sites_results.site_new_flag = $oldOrNew and
            member_sites_results.user_max_test_datetime >= '$from' and member_sites_results.user_max_test_datetime <= '$to'
      ");


  }

  // عدد الاشخاص الذين اجتازة دبلوم او اكثر فى فترة محددة
  // لكن بالتكرار
  // اى عدد الاختبارات الناجحة
  public function getcountSuccessdTestsOfAllSitesSearch($oldOrNew, $from, $to)
  {

    return DB::select("
        SELECT count(*) as total FROM `member_sites_results`
        WHERE user_successed = 1 and member_sites_results.user_max_test_datetime >= '$from' and member_sites_results.user_max_test_datetime <= '$to' and
        member_sites_results.site_new_flag = $oldOrNew
    ");

  }

  // عدد الاشخاص الذين اجتازو دبلوم واحد فقط
  public function getCountUsersSuccessedInOneDiplome($oldOrNew)
  {

      return DB::select("
          select count(DISTINCT(user_id)) as total from (
              Select user_id, count(user_id) as users_tests_count FROM member_sites_results
              where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $oldOrNew
              group By(user_id)
              having users_tests_count = 1 ) a
      ");
  }

  // عدد الاشخاص الذين اجتازو دبلوم واحد فقط فى فترة محددة
  public function getCountUsersSuccessedInOneDiplomeSearch($oldOrNew, $from, $to)
  {

      return DB::select("
          select count(DISTINCT(user_id)) as total from (
              Select user_id, count(user_id) as users_tests_count FROM member_sites_results
              where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $oldOrNew and
              member_sites_results.user_max_test_datetime >= '$from' and member_sites_results.user_max_test_datetime <= '$to'
              group By(user_id)
              having users_tests_count = 1 ) a
      ");

  }

  // عدد الاشخاص الذين اجتازو اكثر من دبلوم
  public function getCountUsersSuccessedMoreThanOneDiplome($oldOrNew)
  {

      return DB::select("
        select count(DISTINCT(user_id)) as total from(
          Select user_id, count(user_id) as users_tests_count FROM member_sites_results
            where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $oldOrNew
            group By(user_id)
            having users_tests_count > 1
            ) a
      ");

  }

   // عدد الاشخاص الذين اجتازو اكثر من دبلوم فى فترة محددة
  public function getCountUsersSuccessedMoreThanOneDiplomeSearch($oldOrNew, $from, $to)
  {

      return DB::select("
        select count(DISTINCT(user_id)) as total from(
          Select user_id, count(user_id) as users_tests_count FROM member_sites_results
            where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $oldOrNew and
            member_sites_results.user_max_test_datetime >= '$from' and member_sites_results.user_max_test_datetime <= '$to'
            group By(user_id)
            having users_tests_count > 1
            ) a
      ");

  }






}
