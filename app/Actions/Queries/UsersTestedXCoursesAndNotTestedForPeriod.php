<?php

namespace App\Actions\Queries;
use DB;

// متبقى له عدد معين من الدورات لم يختبرها
// ومر عليه فترة لم يختبر اى اختبار
class UsersTestedXCoursesAndNotTestedForPeriod
{

    private $from_date;
    private $to_date;
    private $site_id;
    private $more_than_x_courses;
    private $count;
    private $paginate;
    private $func_id;
    private $exprt_type;

    public function __construct( $params=[] )
    {
        $this->from_date = isset($params['from_date']) ? $params['from_date'] : null;
        $this->to_date = isset($params['to_date']) ? $params['to_date'] : null;
        $this->site_id = isset($params['site_id']) ? $params['site_id'] : [];
        $this->more_than_x_courses = isset($params['more_than_x_courses']) ? $params['more_than_x_courses'] : null;
        $this->count = isset($params['count']) ? $params['count'] : null;
        $this->paginate = isset($params['paginate']) ? $params['paginate'] : null;

        $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;

        $this->exprt_type = isset($params['exprt_type']) ? $params['exprt_type'] : 'csv';
    }

    public function collectQuery()
    {
        $countActiveCoursesOfSite = $this->getActiveCoursesOfSite([
          'siteId' => $this->site_id,
          'count' => true
        ]);

        $lessThanXToComplete = $countActiveCoursesOfSite - $this->more_than_x_courses;
        if ($lessThanXToComplete <= 0){
          return null;
        }

        return DB::table('course_tests_results')
          ->join('members','members.id','course_tests_results.user_id')
          ->join('course_site','course_site.course_id','course_tests_results.course_id')

          ->join('course_tests_results as not_tested_period','not_tested_period.id', 'course_tests_results.id')

          ->where('course_site.site_id', $this->site_id )
          ->where('course_tests_results.no_test',  1 )
          ->where('not_tested_period.created_at', '>=', $this->from_date)
          ->where('not_tested_period.created_at', '<=', $this->to_date)

          ->whereNull('members.error_email')
          
          ->select('members.id','members.email','members.name','members.whats_app',
            DB::raw('count(course_tests_results.id) as courses_count'),
            DB::raw('max(course_tests_results.created_at) as max_created_at')
          )
          ->groupBy(['course_tests_results.user_id','course_site.site_id'])
          ->orderBy('members.id')
          ->having('courses_count', '<', $countActiveCoursesOfSite)
          ->having('courses_count', '>=', $lessThanXToComplete)
          ;

    }

    public function getData()
    {
          $q = $this->collectQuery();
          if ($this->paginate){
            return $q->paginate($this->paginate);
          }
          return $q->get();

          // $site_id = isset($params['site_id']) ? $params['site_id'] : null;
          // $more_than_x_courses = isset($params['more_than_x_courses']) ? $params['more_than_x_courses'] : null;
          // $from_date = isset($params['from_date']) ? $params['from_date'] : null;
          // $to_date = isset($params['to_date']) ? $params['to_date'] : null;
          //
          // $count = isset($params['count']) ? $params['count'] : null;
          // $paginate = isset($params['paginate']) ? $params['paginate'] : null;


          // $countActiveCoursesOfSite = $this->getActiveCoursesOfSite([
          //   'siteId'=>$site_id,
          //   'count'=>true
          // ]);
          //
          // $lessThanXToComplete = $countActiveCoursesOfSite - $more_than_x_courses;
          // if ($lessThanXToComplete <= 0){
          //   return null;
          // }

          // SELECT  course_tests_results.id,
          //     		course_tests_results.user_id,
          //             course_tests_results.site_id,
          //             course_tests_results.course_id,
          //     		count(course_tests_results.id) as courses_count,
          //             max(course_tests_results.created_at) as max_created_at
          //          FROM `course_tests_results`
          //     		JOIN course_site on course_site.course_id = course_tests_results.course_id
          //     		JOIN course_tests_results as not_tested_period on not_tested_period.id = course_tests_results.id
          //     			where course_tests_results.no_test = 1
          //     			and course_site.site_id = 11
          //     			and not_tested_period.created_at >= '2022-08-01' and not_tested_period.created_at <= '2022-08-09'
          //     			GROUP by course_tests_results.user_id , course_site.site_id


          // $q = DB::table('course_tests_results')
          //   ->join('members','members.id','course_tests_results.user_id')
          //   ->join('course_site','course_site.course_id','course_tests_results.course_id')
          //
          //   ->join('course_tests_results as not_tested_period','not_tested_period.id', 'course_tests_results.id')
          //
          //   ->where('course_site.site_id', $site_id )
          //   ->where('course_tests_results.no_test',  1 )
          //   ->where('not_tested_period.created_at', '>=', $from_date)
          //   ->where('not_tested_period.created_at', '<=', $to_date)
          //
          //   ->select('members.id','members.email','members.name','members.whats_app',
          //     DB::raw('count(course_tests_results.id) as courses_count'),
          //     DB::raw('max(course_tests_results.created_at) as max_created_at')
          //   )
          //   ->groupBy(['course_tests_results.user_id','course_site.site_id'])
          //   ->having('courses_count', '<', $countActiveCoursesOfSite)
          //   ->having('courses_count', '>=', $lessThanXToComplete)
            ;



    }

    public function exportData()
    {

          $exportService = new \App\Services\ExportService();
          if($this->exprt_type == 'csv') {
            return $exportService->exportCsv(['members.id','name','email'], $this->collectQuery());
          }

    }

    public function getEmails()
    {

      $q = $this->collectQuery();

      return $q->leftjoin('emails_to_send_queries_members', function ($join) {
              $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id', $this->func_id);
      })
      ->whereNull('emails_to_send_queries_members.id')->first();

      // $site_id = isset($params['site_id']) ? $params['site_id'] : null;
      // $more_than_x_courses = isset($params['more_than_x_courses']) ? $params['more_than_x_courses'] : null;
      // $from_date = isset($params['from_date']) ? $params['from_date'] : null;
      // $to_date = isset($params['to_date']) ? $params['to_date'] : null;
      //
      // $func_id = isset($params['func_id']) ? $params['func_id'] : null;
      // $count = isset($this->params['count']) ? $this->params['count'] : null;
      // $paginate = isset($this->params['paginate']) ? $this->params['paginate'] : null;
      //
      // $countActiveCoursesOfSite = $this->getActiveCoursesOfSite([
      //   'siteId'=>$site_id,
      //   'count'=>true
      // ]);
      //
      //
      // $lessThanXToComplete = $countActiveCoursesOfSite - $more_than_x_courses;
      // if ($lessThanXToComplete <= 0){
      //   return null;
      // }
      //
      //
      // $q = DB::table('course_tests_results')
      //   ->join('members','members.id','course_tests_results.user_id')
      //   ->join('course_site','course_site.course_id','course_tests_results.course_id')
      //
      //   ->join('course_tests_results as not_tested_period','not_tested_period.id', 'course_tests_results.id')
      //
      //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
      //         $join->on('emails_to_send_queries_members.user_id','members.id')
      //         ->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      //   })
      //   ->whereNull('emails_to_send_queries_members.id')
      //
      //   ->where('course_site.site_id', $site_id )
      //   ->where('course_tests_results.no_test',  1 )
      //   ->where('not_tested_period.created_at', '>=', $from_date)
      //   ->where('not_tested_period.created_at', '<=', $to_date)
      //
      //   ->select('members.id','members.email','members.name','members.whats_app',
      //     DB::raw('count(course_tests_results.id) as courses_count'),
      //     DB::raw('max(course_tests_results.created_at) as max_created_at')
      //   )
      //   ->groupBy(['course_tests_results.user_id','course_site.site_id'])
      //   ->having('courses_count', '<', $countActiveCoursesOfSite)
      //   ->having('courses_count', '>=', $lessThanXToComplete)->first();


      // $q = DB::table('course_tests_results')
      //   ->join('members','members.id','course_tests_results.user_id')
      //   ->join('course_site','course_site.course_id','course_tests_results.course_id')
      //
      //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
      //         $join->on('emails_to_send_queries_members.user_id','members.id')
      //         ->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      //   })
      //   ->whereNull('emails_to_send_queries_members.id')
      //
      //   ->where('course_site.site_id',  $site_id )
      //   ->where('course_tests_results.no_test',  1 )
      //   ->select('members.id','members.email',
      //     DB::raw('count(course_tests_results.id) as courses_count'),
      //     DB::raw('max(course_tests_results.created_at) as max_created_at')
      //   )
      //   ->groupBy(['course_tests_results.user_id','course_site.site_id'])
      //   ->having('courses_count', '<', $countActiveCoursesOfSite)
      //   ->having('courses_count', '>=', $lessThanXToComplete)
      //   ->having('max_created_at', '<', $from_date)
      //   ->first();

        return $q;

    }

    private function getActiveCoursesOfSite($params=[])
    {
       $globalService = new \App\Services\GlobalService();
       return $globalService->getActiveCourses($params);
    }

    public function AssignNotificationsToUser($notification_id,$params=[])
    {

          $site_id = isset($params['site_id']) ? $params['site_id'] : null;
          $more_than_x_courses = isset($params['more_than_x_courses']) ? $params['more_than_x_courses'] : null;

          $countActiveCoursesOfSite = $this->getActiveCoursesOfSite([
            'siteId'=>$site_id,
            'count'=>true
          ]);

          $lessThanXToComplete = $countActiveCoursesOfSite - $more_than_x_courses;
          if ($lessThanXToComplete <= 0){
            return null;
          }

          $notificationId = $notification_id;

          // same query as getData() above
          DB::table('course_tests_results')
            ->join('members','members.id','course_tests_results.user_id')
            ->join('course_site','course_site.course_id','course_tests_results.course_id')
            ->where('course_tests_results.site_id',  $site_id )
            ->where('course_tests_results.no_test',  1 )
            ->select('members.id')
            ->groupBy(['course_tests_results.user_id','course_site.site_id'])
            ->having(DB::raw('count(course_tests_results.id)'), '>', $lessThanXToComplete)
            ->orderBy('members.id')->chunk(50, function ($items) use($notificationId) {
                $inserts = [];
                foreach ($items as $item) {
                    $inserts[] = [
                      'user_id' => $item->id,
                      'notification_id' => $notificationId,
                      'is_active' => 1
                    ];
                }
                DB::table('notifications_inner_members')->insert($inserts);
          });

          return true;
    }

}
