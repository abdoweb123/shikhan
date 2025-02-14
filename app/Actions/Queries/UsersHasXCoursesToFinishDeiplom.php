<?php

namespace App\Actions\Queries;
use DB;

// 'من بقى له عدد معين من الدورات لاتمام الدبلوم
class UsersHasXCoursesToFinishDeiplom
{

    private $site_id;
    private $more_than_x_courses;
    private $count;
    private $paginate;
    private $func_id;
    private $exprt_type;

    public function __construct( $params=[] )
    {

        $this->site_id = isset($params['site_id']) ? $params['site_id'] : null;
        $this->more_than_x_courses = isset($params['more_than_x_courses']) ? $params['more_than_x_courses'] : null;
        $this->count = isset($params['count']) ? $params['count'] : null;
        $this->paginate = isset($params['paginate']) ? $params['paginate'] : null;

        $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;
        $this->exprt_type = isset($params['exprt_type']) ? $params['exprt_type'] : 'csv';

    }

    public function collectQuery()
    {

          $countActiveCoursesOfSite = $this->getActiveCoursesOfSite([
            'siteId'=> $this->site_id,
            'count'=> true
          ]);

          $lessThanXToComplete = $countActiveCoursesOfSite - $this->more_than_x_courses;
          $lessThanXToComplete = abs($lessThanXToComplete);

          // if ($lessThanXToComplete <= 0){
          //   return null;
          // }

          return DB::table('users_results')
            ->join('members','members.id','users_results.user_id')
            ->where('users_results.site_id',  $this->site_id )
            ->whereNull('members.error_email')
            ->select('members.id','members.email','members.name','members.whats_app',DB::raw('count(users_results.user_id) as tests_count,'.$countActiveCoursesOfSite))
            ->groupBy(['users_results.user_id'])
            ->orderBy('members.id')
            // ->having(DB::raw('count(users_results.user_id)'), '>=', $lessThanXToComplete)
            ->havingRaw('count(users_results.user_id) = ' . $lessThanXToComplete . ' and count(users_results.user_id) < ' . $countActiveCoursesOfSite )
            ;
    }

    public function getData()
    {

        $q = $this->collectQuery();
        if (! $q){
          return [];
        }

        if ($this->paginate){
          return $q->paginate($this->paginate);
        }

        return $q->get();

    }

    public function exportData()
    {

          $exportService = new \App\Services\ExportService();
          if($this->exprt_type == 'csv') {
            return $exportService->exportCsv( ['members.id','name','email'], $this->collectQuery() );
          }

    }

    public function getEmails()
    {

        $q = $this->collectQuery();

        return $q->leftjoin('emails_to_send_queries_members', function ($join) {
                $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id', $this->func_id);
        })
        ->whereNull('emails_to_send_queries_members.id')->first();


      // ->leftjoin('emails_to_send_queries_members', function ($join){
      //       $join->on('emails_to_send_queries_members.user_id','members.id')
      //       ->where('emails_to_send_queries_members.emails_to_send_queries_id', $this->func_id);
      // })
      // ->whereNull('emails_to_send_queries_members.id')


      // $site_id = isset($params['site_id']) ? $params['site_id'] : null;
      // $more_than_x_courses = isset($params['more_than_x_courses']) ? $params['more_than_x_courses'] : null;
      // $func_id = isset($params['func_id']) ? $params['func_id'] : null;
      // $count = isset($this->params['count']) ? $this->params['count'] : null;
      // $paginate = isset($this->params['paginate']) ? $this->params['paginate'] : null;

      // $countActiveCoursesOfSite = $this->getActiveCoursesOfSite([
      //   'siteId'=>$site_id,
      //   'count'=>true
      // ]);
      //
      // $lessThanXToComplete = $countActiveCoursesOfSite - $more_than_x_courses;
      // if ($lessThanXToComplete <= 0){
      //   return null;
      // }
      //
      //
      // $q =  DB::table('users_results')
      //     ->join('members','members.id','users_results.user_id')
      //
      //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
      //         $join->on('emails_to_send_queries_members.user_id','members.id')
      //         ->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      //   })
      //   ->whereNull('emails_to_send_queries_members.id')
      //
      //   ->where('users_results.site_id',  $site_id )
      //   ->select('members.id','members.email','members.name','members.whats_app',DB::raw('count(users_results.user_id)'))
      //   ->groupBy(['users_results.user_id'])
      //   ->having(DB::raw('count(users_results.user_id)'), '=', $lessThanXToComplete)->first();
      //
      //   return $q;

    }

    private function getActiveCoursesOfSite($params=[])
    {
       $globalService = new \App\Services\GlobalService();
       return $globalService->getActiveCourses($params);
    }

    public function AssignNotificationsToUser($notification_id)
    {

        $q = $this->collectQuery();
        if (! $q){
          return [];
        }

        $notificationId = $notification_id;

        $q->orderBy('members.id')->chunk(50, function ($items) use($notificationId) {
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



          // $site_id = isset($params['site_id']) ? $params['site_id'] : null;
          // $more_than_x_courses = isset($params['more_than_x_courses']) ? $params['more_than_x_courses'] : null;

          // $countActiveCoursesOfSite = $this->getActiveCoursesOfSite([
          //   'siteId'=>$site_id,
          //   'count'=>true
          // ]);
          //
          // $lessThanXToComplete = $countActiveCoursesOfSite - $more_than_x_courses;
          // if ($lessThanXToComplete <= 0){
          //   return null;
          // }
          //
          // $notificationId = $notification_id;

          // same query as getData() above
          // DB::table('course_tests_results')
          //   ->join('members','members.id','course_tests_results.user_id')
          //   ->join('course_site','course_site.course_id','course_tests_results.course_id')
          //   ->where('course_tests_results.site_id',  $site_id )
          //   ->where('course_tests_results.no_test',  1 )
          //   ->select('members.id')
          //   ->groupBy(['course_tests_results.user_id','course_site.site_id'])
          //   ->having(DB::raw('count(course_tests_results.id)'), '>', $lessThanXToComplete)
          //   ->orderBy('members.id')->chunk(50, function ($items) use($notificationId) {
          //       $inserts = [];
          //       foreach ($items as $item) {
          //           $inserts[] = [
          //             'user_id' => $item->id,
          //             'notification_id' => $notificationId,
          //             'is_active' => 1
          //           ];
          //       }
          //       DB::table('notifications_inner_members')->insert($inserts);
          // });

          // return true;
    }

}
