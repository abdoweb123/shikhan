<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;

class StatisticController extends Controller
{

    public function __construct()
    { }

    public function getDailyRegisterd()
    {

        $data['dailyRegisterd'] =
          \App\member::selectRaw("COUNT(*) as member_counts, DATE_FORMAT(created_at, '%Y-%m-%d') date")
          ->groupBy('date')
          ->orderBy('date', 'DESC')
          ->get();
        $data['dailyRegisterdSum'] = $data['dailyRegisterd']->sum('member_counts');



        // ------------------------
        $data['dailyTestedNoDublicate'] =
          \App\course_test_result::selectRaw("COUNT(DISTINCT(user_id)) as tested_members_counts, DATE_FORMAT(created_at, '%Y-%m-%d') date")
          ->groupBy(['date'])
          ->get();

        $data['dailyTestedNoDublicateSum'] = \App\course_test_result::selectRaw("COUNT(DISTINCT(user_id)) as tested_members_sum")->first();
        // ------------------------




        // ------------------------
        $data['dailyCertificatesCount'] =
          \App\course_test_result::where('degree', '>=', 50 )->selectRaw("COUNT(DISTINCT user_id, course_id) as certificates_counts, DATE_FORMAT(created_at, '%Y-%m-%d') date")
          ->groupBy(['date'])
          ->get();


        $data['certificatesCount'] = $this->getCertificatesCount('count');
        // ------------------------




        // ------------------------
        $data['dailyTested'] =
          \App\course_test_result::selectRaw("COUNT(*) as tested_members_counts, DATE_FORMAT(created_at, '%Y-%m-%d') date")
          ->groupBy(['date'])
          ->get();

        $data['dailyTestedSum'] = \App\course_test_result::selectRaw("COUNT(*) as tested_members_sum")->first();
        // ------------------------



        $data['notTestedCount'] = $this->getNotTestedCount('count');


        return view ('back.content.statistic.index',$data);
    }

    public function getDaily()
    {

        $data['testedWithoutDublicateCount'] = $this->getTestedWithoutDublicateCount('count');
        $data['testedCount'] = $this->getTestedCount('count');
        $data['notTestedCount'] = $this->getNotTestedCount('count');
        $data['registeredCount'] = $this->getRegisteredCount('count');
        $data['certificatesCount'] = $this->getCertificatesCount('count');

        return view ('back.content.statistic.daily', $data);

    }

    // المختبرين بدون تكرار
    public function getTestedWithoutDublicateCount($get = null)
    {
        if ($get == 'count'){
            return DB::select('select count(*) as total_count
              FROM `members`
              Left JOIN countries on members.country_id = countries.id
              WHERE members.id in ( SELECT user_id from course_tests_results )
              and members.id not in ("224","65")'
            );
        }

        return DB::select('select members.id, members.name, members.email, members.country_name_out, countries.arabic as member_country, members.city
          FROM `members`
          Left JOIN countries on members.country_id = countries.id
          WHERE members.id in ( SELECT user_id from course_tests_results )
          and members.id not in ("224","65")'
        );

    }


    // المختبرين
    public function getTestedCount($get = null)
    {
        if ($get == 'count'){
            return DB::select('select count(*) as total_count
              FROM `course_tests_results`
              JOIN members on members.id = user_id
              JOIN courses on courses.id = course_id
              WHERE user_id not in ("224","65")'
            );
        }

        return DB::select('select courses.title, members.id, members.name, members.email, members.country_name_out, (select countries.arabic from countries where countries.id = members.country_id) as member_country, members.city, course_tests_results.course_id, course_tests_results.degree, course_tests_results.rate  FROM `course_tests_results`
          JOIN members on members.id = user_id
          JOIN courses on courses.id = course_id
          WHERE user_id not in ("224","65")'
        );


    }

    // غير المختبرين
    public function getNotTestedCount($get = null)
    {
        if ($get == 'count'){
            return DB::select('select count(*) as total_count
              FROM `members`
              WHERE members.id not in (
                  SELECT user_id from `course_tests_results`
              )'
            );
        }

        return DB::select('select
          members.id, members.name, members.email, members.phone, members.whats_app, members.gender, members.birthday, members.created_at, members.country_name_out, members.country_name, (select countries.arabic from countries where countries.id = members.country_id) as member_country, members.city
          FROM `members`
          WHERE members.id not in (
              SELECT user_id from `course_tests_results`
          )'
        );

    }

    // المسجلين
    public function getRegisteredCount($get = null)
    {
        if ($get == 'count'){
            return DB::select('select count(*) as total_count
              FROM `members`
              Left JOIN countries on members.country_id = countries.id'
            );

        }

        return DB::select('select members.id, members.name, members.email, members.phone, members.whats_app, members.gender, members.birthday, members.created_at, members.country_name_out, countries.arabic as member_country, members.city
          FROM `members`
          Left JOIN countries on members.country_id = countries.id'
        );

    }

    // عدد الشهادات
    public function getCertificatesCount($get = null)
    {
        if ($get == 'count'){
            return DB::select('select count(distinct user_id, course_id) as total_count from `course_tests_results` WHERE degree >= 50 ');
        }

        // return DB::select('select count(*) from ( SELECT * FROM `course_tests_results` WHERE degree >= 50 ) c GROUP by user_id, course_id');

    }

    public function export(Request $request, $columns, $query, $chunkSize = 1000)
    {

        $exportService = new \App\Services\ExportService();

        if ($request->statistic == 'tested_without_dublicate_count'){
          return $exportService->exportCsv(['members.id', 'members.name', 'members.email', 'members.country_name_out', 'countries.arabic as member_country', 'members.city'], $this->getTestedWithoutDublicateCount());
        }

        if ($request->statistic == 'tested_count'){
          return $exportService->exportCsv(['courses.title', 'members.id', 'members.name', 'members.email', 'members.country_name_out', 'member_country', 'members.city', 'course_tests_results.course_id', 'course_tests_results.degree', 'course_tests_results.rate'], $this->getTestedCount());
        }

        if ($request->statistic == 'not_tested_count'){
          return $exportService->exportCsv(['members.id', 'members.name', 'members.email', 'members.phone', 'members.whats_app', 'members.gender', 'members.birthday', 'members.created_at', 'members.country_name_out', 'members.country_name', 'member_country', 'members.city'], $this->getNotTestedCount());
        }

        if ($request->statistic == 'registered_count'){
          return $exportService->exportCsv(['members.id', 'members.name', 'members.email', 'members.phone', 'members.whats_app', 'members.gender', 'members.birthday', 'members.created_at', 'members.country_name_out', 'member_country', 'members.city'], $this->getRegisteredCount());
        }


    }


}
