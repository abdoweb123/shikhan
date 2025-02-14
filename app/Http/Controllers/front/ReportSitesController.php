<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App;
use DB;

class ReportSitesController extends Controller
{

  public function sitesStatistics(Request $request)
  {

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 600);

        $request->flash();

        $sts = new \App\Services\StatisticService();

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
              	) subs_count_period ,
                (
                	SELECT COUNT(course_id)	FROM `course_site`
            		  JOIN courses on courses.id = course_site.course_id
            		  where course_site.site_id = sites.id
                ) courses_count ,
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
                ) courses_count_active
            FROM sites order By new_flag, sites.id
        ");


        foreach ($data['sites'] as $site) {

            // عدد الاختبارات فى الدبلوم بالتكرار
            // tests_count = count_tests_with_repeated
            $site->tests_count_with_repeated = $sts->whereSiteId($site->id)->getTestsCountOfSite_WithRepeated()[0]->total;
            // عدد الاختبارات فى الدبلوم فى فتة بالتكرار
            // tests_count_search = count_tests_period_with_repeated
            $site->tests_count_period_with_repeated = $sts->whereSiteId($site->id)->from($from)->to($to)->getTestsCountOfSiteOfPeriod_WithRepeated()[0]->total;


            // عدد المختبرين فى الدبلوم
            // tested_users = count_tested_users
            $site->tested_users_count = $sts->whereSiteId($site->id)->getTestedUsersCountOfSite()[0]->total;
            // عدد المختبرين فى الدبلوم فى فترة
            // tested_users_search = count_tested_users_period
            $site->tested_users_count_period = $sts->whereSiteId($site->id)->from($from)->to($to)->getTestedUsersCountOfSiteOfPeriod()[0]->total;


            //عدد الناجحين فى الدبلوم
            // successedUsers = successed_users_count
            $site->successed_users_count = $sts->whereSiteId($site->id)->getSuccessedUsersCountOfSite()[0]->total;
            // عدد الناجحين فى الدبلوم فى فترة
            // successedUsersSearch = successed_users_count_period
            $site->successed_users_count_period = $sts->whereSiteId($site->id)->from($from)->to($to)->getSuccessedUsersCountOfSiteOfPeriod()[0]->total;


            $site->cirts_count = 0;
            if ($site->courses_count){ // my be no courses in this site
                // عدد الاختبارات الناجحة فى الدبلوم - عدد الشهادات
                // cirtsCount = count_cirts
                $site->cirts_count = $sts->whereSiteId($site->id)->getSuccessedTestsCountOfSite()[0]->total;
                // عدد الاختبارات الناجحة فى الدبلوم فى فترة - عدد الشهادات
                // cirtsCountSearch = count_cirts_period
                $site->cirts_count_period = $sts->whereSiteId($site->id)->getSuccessedTestsCountOfSiteOfPeriod()[0]->total;
            }

        }


        $data['counts'] = collect($data['sites'])->groupBy('new_flag')->map(function ($site, $key) use($sts, $from, $to) {
            $oldOrNew = $site->first()->new_flag;

            // اجمالى الاشتراكات فى المرحلة
            // $item->subs_count_total = $item->sum('subs_count');
            $site->total_stage_subs = $site->sum('subs_count');
            // الجمالى الاشتراكات فى المرحلة فى فترة
            // $item->subs_count_total_search = $item->sum('subs_count_search');
            $site->total_stage_subs_period = $site->sum('subs_count_period');




            // اجمالى الاشتراكات فى الدورات
            // $subs_courses_count_total = 0;
            $total_courses_subs = 0;
            // اجمالى الاشتراكاتفى الدورات فى فترة
            // $subs_courses_count_total_search = 0;
            $total_courses_subs_period = 0;
            foreach ($site as $child) {
                $total_courses_subs = $total_courses_subs + ($child->subs_count * $child->courses_count);
                $total_courses_subs_period = $total_courses_subs_period + ($child->subs_count_period * $child->courses_count);
            }
            $site->total_courses_subs = $total_courses_subs;
            $site->total_courses_subs_period = $total_courses_subs_period;




            // اجمالى الاختبارات بالتكرار فى مرحلة
            // $item->tests_count_total = $item->sum('tests_count');
            $site->total_stage_tests_with_repeated = $site->sum('tests_count_with_repeated');
            // اجمالى الاختبارات فى المرحلة بدون تكرار
            $site->total_stage_tests = $sts->whereStage($oldOrNew)->getTestsCountOfStage()[0]->total; //   $site->sum('tests_count');


            // $item->tests_count_total_search = $item->sum('tests_count_search');
            // اجمالى الاختبارات بالتكرار فى مرحلة فى فترة
            $site->total_stage_tests_with_repeated_period = $site->sum('tests_count_period_with_repeated');
            // اجمالى الاختبارات فى المرحلة فى فترة بدون تكرار
            $site->total_stage_tests_period = $sts->whereStage($oldOrNew)->from($from)->to($to)->getTestsCountOfStageOfPeriod()[0]->total;// $site->sum('tests_count_period');



            // اجمالى عدد المختبرين فى المرحلة بالتكرار
            // تجميع ......
            // tested_users_total
            $site->total_stage_tested_users_with_repeated = $site->sum('tested_users_count');
            // اجمالى عدد المختبرين فى المرحلة بدون تكرار
            $site->total_stage_tested_users = $sts->whereStage($oldOrNew)->getTestedUsersCountOfStage()[0]->total; // $item->tested_users_total_no_dublicate = $this->getCountOfTestedUsers($oldOrNew)[0]->total;


            // اجمالى عدد المختبرين فى المرحلة فى فترة بالتكرار
            // تجميع ........
            // tested_users_total_search
            $site->total_stage_tested_users_period_with_repeated = $site->sum('tested_users_count_period');
            // اجمالى عدد المختبرين فى المرحلة فى فترة بدون تكرار
            $site->total_stage_tested_users_period = $sts->whereStage($oldOrNew)->from($from)->to($to)->getTestedUsersCountOfStageOfPeriod()[0]->total; // $this->getCountOfTestedUsersSearch($oldOrNew, $from, $to)[0]->total;




            // اجمالى عدد الكورسات فى المرحلة
            // courses_count_total = total_stage_courses
            $site->total_stage_courses = $site->sum('courses_count');
            // اجمالى عدد الكورسات الفعالة فى المرحلة
            // active_courses_count_total = total_stage_courses_active
            $site->total_stage_courses_active = $site->sum('courses_count_active');



            // اجمالى عدد الناجحين فى المرحلة بالتكرار
            // تجميع لعدد الناجحين فى الدبلومات داخل كل مرحلة
            // قد يكون الطالب ناجح فى دبلوم او اكثر داخل المرحلة
            // successedUsers_total = total_stage_successed_users_with_repeated
            $site->total_stage_successed_users_with_repeated = $site->sum('successed_users_count');
            // اجمالى عدد الناجحين فى المرحلة بدون تكرار
            // successedUsers_total_no_dublicate = total_stage_successed_users
            $site->total_stage_successed_users = $sts->whereStage($oldOrNew)->getSuccessdUsersCountOfStage()[0]->total;



            // اجمالى عدد الناجحين فى المرحلة فى فترة بالتكرار
            // تجميع لعدد الناجحين فى الدبلومات داخل كل مرحلة
            // قد يكون الطالب ناجح فى دبلوم او اكثر داخل المرحلة
            // successedUsers_total_search = total_stage_successed_users_period_with_repeated
            $site->total_stage_successed_users_period_with_repeated = $site->sum('successed_users_count_period');
            //  اجمالى عدد الناجحين فى المرحلة فى فترة بدون تكرار
            // successedUsers_total_search_no_dublicate = total_stage_successed_users_period
            $site->total_stage_successed_users_period = $sts->whereStage($oldOrNew)->from($from)->to($to)->getSuccessdUsersCountOfStageOfPeriod()[0]->total;



            // اجمالى عدد الاختبارات الناجحة فى المرحلة بالتكرار - اجمالى عدد الشهادات الناجحة فى المرحلة بالتكرار
            // تجميع لعدد الاختبارات الناجحة للدبلومات داخل المرحلة
            // قد يكون الاختبار مكرر داخل عدة دبلومات فى نفس المرحلة
            // cirtsCount_total = total_stage_cirts_count_with_repeated
            $site->total_stage_cirts_count_with_repeated = $site->sum('cirts_count');
            // اجمالى عدد الاختبارات الناجحة فى المرحلة بدون تكرار
            // cirtsCount_total_no_dublicate = total_stage_cirts_count
            $site->total_stage_cirts_count = $sts->whereStage($oldOrNew)->getSuccessedTestsCountOfStage()[0]->total; // $this->getCountOfCertficiations($oldOrNew)[0]->total;


            // اجمالى عدد الاختبارات الناجحة فى المرحلة
            // تجميع لعدد الاختبارات الناجحة للدبلومات داخل المرحلة
            // قد يكون الاختبار مكرر داخل عدة دبلومات فى نفس المرحلة
            // cirtsCount_total_search = total_stage_cirts_count_period_with_repeated
            $site->total_stage_cirts_count_period_with_repeated = $site->sum('cirts_count_period');
            // اجمالى عدد الاختبارات الناجحة فى المرحلة فى فترة بدون تكرار
            // cirtsCount_total_search_no_dublicate = total_stage_cirts_count_period
            $site->total_stage_cirts_count_period = $sts->whereStage($oldOrNew)->from($from)->to($to)->getSuccessedTestsCountOfStageOfPeriod()[0]->total; // $this->getCountOfCertficiationsSearch($oldOrNew, $from, $to)[0]->total;

            return $site;

        });


        return view('front.reports_global.sites_statistics_static', $data);

  }

}
