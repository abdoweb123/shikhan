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

class ReportStaticController extends Controller
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

      $sts = new \App\Services\StatisticService();

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





                    // $data['countOfMembers'] = DB::select("select count(*) as total FROM members")[0]->total;
                    $data['countOfMembers'] = $sts->getMembersCount()[0]->total;


                    $data['countOfSites']= site::where('new_flag',$oldOrNew)->count();


                    // $data['countOfCourses'] = DB::select("
                    //   Select count(*) as total from
                    //     ( select course_site.course_id as total FROM courses
                    //       join course_site on course_site.course_id = courses.id
                    //       join sites on sites.id = course_site.site_id
                    //       where sites.new_flag = $oldOrNew
                    //       GROUP by course_site.course_id
                    //     ) courses
                    //   ")[0]->total;
                    $data['countOfCourses'] = $sts->whereStage($oldOrNew)->getCoursesCountOfStage()[0]->total;



                    // $countOfActiveCourses = DB::select("
                    //       select courses.id as total FROM courses
                    //       join course_site on course_site.course_id = courses.id
                    //       join sites on sites.id = course_site.site_id
                    //       where courses.status = 1 and
                    //       courses.deleted_at is null and
                    //       courses.exam_at < now() and
                    //       sites.new_flag = $oldOrNew
                    //       GROUP by course_site.course_id
                    //     ");
                    // $data['countOfActiveCourses'] = count($countOfActiveCourses);
                    $data['countOfActiveCourses'] =  $sts->whereStage($oldOrNew)->getCoursesCountActiveOfStage()[0]->total;



                    //عدد الشهادات
                    // $data['countOfCertficiations'] = $this->getCountOfCertficiations($oldOrNew)[0]->total;
                    $data['countOfCertficiations'] =  $sts->whereStage($oldOrNew)->getSuccessedTestsCountOfStage()[0]->total;




                    // عدد الاختبارات
                    // $data['countOfTests'] = $this->getCountOfTests($oldOrNew)[0]->total;
                    $data['countOfTests'] = $sts->whereStage($oldOrNew)->getTestsCountOfStage()[0]->total;





                    // عدد المختبرين
                    // $data['countOfTestedUsers'] = $this->getCountOfTestedUsers($oldOrNew)[0]->total;
                    $data['countOfTestedUsers'] = $sts->whereStage($oldOrNew)->getTestedUsersCountOfStage()[0]->total;







                    // اجمالى الاشتراكات فى المرحلة
                    // $data['countOfSubsSites'] = DB::select("
                    //       select count(*) as total
                    //       FROM site_subscriptions
                    //       join sites on sites.id = site_subscriptions.site_id
                    //       where sites.new_flag = $oldOrNew
                    //     ")[0]->total;
                    $data['countOfSubsSites'] = DB::select("
                          select sum(total) as total from members_sites_subscriptions where site_new_flag = $oldOrNew
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
                    // $data['countSuccessdUsersOfAllSites']  = $this->getCountSuccessdUsersOfAllSites($oldOrNew)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    $data['countSuccessdUsersOfAllSites']  = $sts->whereStage($oldOrNew)->getSuccessdUsersCountOfStage()[0]->total;


                    // عدد الاختبارات الناجحة اى بتكرار الشخص
                    // عدد الدبلومات التى تم اجتيازاها بتكرار الدبلومات
                    // $data['countSuccessdTestsOfAllSites']  = $this->getCountSuccessdTestsOfAllSites($oldOrNew)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    $data['countSuccessdTestsOfAllSites'] = $sts->whereStage($oldOrNew)->getSuccessdSitesTestsCountOfStage()[0]->total;


                    // عدد الاشخاص الذين اجتازو دبلوم واحد فقط
                    // $data['countUsersSuccessedInOneDiplome']  = $this->getCountUsersSuccessedInOneDiplome($oldOrNew)[0]->total;
                    $data['countUsersSuccessedInOneDiplome'] = $sts->whereStage($oldOrNew)->getSuccessdUsersInOneDiplomeCountOfStage()[0]->total;


                    // عدد الاشخاص الذين اجتازو اكثر من دبلوم
                    // $data['countUsersSuccessedMoreThanOneDiplome'] = $this->getCountUsersSuccessedMoreThanOneDiplome($oldOrNew)[0]->total;
                    $data['countUsersSuccessedMoreThanOneDiplome'] = $sts->whereStage($oldOrNew)->getSuccessdUsersMoreThanOneDiplomeCountOfStage()[0]->total;

            } else {
                    // search ajax


                    //
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



                    // عدد الطلاب الذين اشتركو فى فترة معين
                    // $data['countOfMembersSearch'] = DB::select("select count(*) as total FROM members where created_at >= '$from' and created_at <= '$to' ")[0]->total;
                    $data['countOfMembersSearch'] = $sts->from($from)->to($to)->getMembersCountOfPeriod()[0]->total;


                    //  عدد الاختبارات فى المرحلة فى فترة
                    // $data['countOfTestsSearch'] = $this->getCountOfTestsSearch($oldOrNew, $from, $to)[0]->total;
                    $data['countOfTestsSearch'] = $sts->whereStage($oldOrNew)->from($from)->to($to)->getTestsCountOfStageOfPeriod()[0]->total;


                    // عدد المختبرين فى المرحلة فى فترة
                    // $data['countOfTestedUsersSearch'] = $this->getCountOfTestedUsersSearch($oldOrNew, $from, $to)[0]->total;
                    $data['countOfTestedUsersSearch'] = $sts->whereStage($oldOrNew)->from($from)->to($to)->getTestedUsersCountOfStageOfPeriod()[0]->total;




                    // مختبرين من الجدد
                    // عدد المختبرين من الطلاب الذين سجلو فى هذه الفترة
                    // $data['countOfTestedUsersOfRegisteredUsersSearch'] = DB::select("
                    //   select count(DISTINCT(course_tests_results.user_id)) as total FROM course_tests_results
                    //   join sites on sites.id = course_tests_results.site_id
                    //   join members on course_tests_results.user_id = members.id
                    //   where sites.new_flag = $oldOrNew
                    //   and members.created_at >= '$from' and members.created_at <= '$to'
                    //   and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
                    // ")[0]->total;
                    $data['countOfTestedUsersOfRegisteredUsersSearch'] = DB::select("
                      select count(DISTINCT(member_courses_results.user_id)) as total FROM member_courses_results
                      join members on member_courses_results.user_id = members.id
                      where member_courses_results.site_new_flag = $oldOrNew
                      and members.created_at >= '$from' and members.created_at <= '$to'
                      and member_courses_results.test_created_at >= '$from' and member_courses_results.test_created_at <= '$to'
                    ")[0]->total;








                    //  اجمالى الاشتراكات فى المرحلة فى فترة
                    // $data['countOfSubsSitesSearch'] = DB::select("
                    //       select count(*) as total FROM site_subscriptions
                    //       join sites on sites.id = site_subscriptions.site_id
                    //       where sites.new_flag = $oldOrNew
                    //       and site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
                    //   ")[0]->total;
                    $data['countOfSubsSitesSearch'] = DB::select("
                          select sum(total) as total from members_sites_subscriptions where site_new_flag = $oldOrNew
                          and members_sites_subscriptions.date >= '$from' and members_sites_subscriptions.date <= '$to'
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
                    // $data['countOfSubsUsersOfRegisteredUsersSearch'] = DB::select("
                    //       select count(DISTINCT(site_subscriptions.user_id)) as total FROM site_subscriptions
                    //       join sites on sites.id = site_subscriptions.site_id and sites.new_flag = $oldOrNew
                    //       where site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
                    //       and site_subscriptions.user_id in (
                    //           select id as total FROM members where created_at >= '$from' and created_at <= '$to'
                    //         )
                    //   ")[0]->total;
                    $data['countOfSubsUsersOfRegisteredUsersSearch'] = DB::select("
                          select count(DISTINCT(site_subscriptions.user_id)) as total FROM site_subscriptions
                          join sites on sites.id = site_subscriptions.site_id and sites.new_flag = $oldOrNew
                          join members on site_subscriptions.user_id = members.id and members.created_at >= '$from' and members.created_at <= '$to'
                          where site_subscriptions.created_at >= '$from' and site_subscriptions.created_at <= '$to'
                      ")[0]->total;




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
                    // $data['countSuccessdUsersOfAllSitesSearch']  = $this->getcountSuccessdUsersOfAllSitesSearch($oldOrNew, $from, $to)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    // عدد الاشخاص الذين اجتازوا دبلوم أو أكثر فى مرحلة فى فترة - عدد الناجحين
                    $data['countSuccessdUsersOfAllSitesSearch']  = $sts->whereStage($oldOrNew)->from($from)->to($to)->getSuccessdUsersCountOfStageOfPeriod()[0]->total;


                    // عدد الاختبارات الناجحة اى بتكرار الشخص
                    // $data['countSuccessdTestsOfAllSitesSearch']  = $this->getcountSuccessdTestsOfAllSitesSearch($oldOrNew, $from, $to)[0]->total; // اذا اختبر طالب فى دورة يتم احتساب اختبارة فى الدبلومين - من الكويرى العام
                    // عدد الاختبارات الناجحة اى بتكرار الشخص
                    // عدد الدبلومات التى تم اجتيازاها بتكرار الدبلومات فى مرحلة فى فترة
                    $data['countSuccessdTestsOfAllSitesSearch'] = $sts->whereStage($oldOrNew)->from($from)->to($to)->getSuccessdSitesTestsCountOfStageOfPeriod()[0]->total;


                    // عدد الاشخاص الذين اجتازو دبلوم واحد فقط
                    // $data['countUsersSuccessedInOneDiplomeSearch']  = $this->getCountUsersSuccessedInOneDiplomeSearch($oldOrNew, $from, $to)[0]->total;
                    // عدد الاشخاص الذين اجتازو دبلوم واحد فقط فى مرحلة فى فترة
                    $data['countUsersSuccessedInOneDiplomeSearch'] = $sts->whereStage($oldOrNew)->from($from)->to($to)->getSuccessdUsersInOneDiplomeCountOfStageOfPeriod()[0]->total;


                    // عدد الاشخاص الذين اجتازو اكثر من دبلوم فى فترة محددة
                    // $data['countUsersSuccessedMoreThanOneDiplomeSearch']  = $this->getCountUsersSuccessedMoreThanOneDiplomeSearch($oldOrNew, $from, $to)[0]->total;
                    // عدد الاشخاص الذين اجتازو اكثر من دبلوم
                    $data['countUsersSuccessedMoreThanOneDiplomeSearch'] = $sts->whereStage($oldOrNew)->from($from)->to($to)->getSuccessdUsersMoreThanOneDiplomeCountOfStageOfPeriod()[0]->total;


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

}
