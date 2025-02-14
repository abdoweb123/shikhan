<?php

namespace App\Actions\Queries;
use DB;

// اختبر عدد معين من الدورات ونجح
// مثلا اختبر 3 مواد فى دبلوم ونجح فيهم نرسل رساله لتكملة الدبلوم
class UsersTestedXCoursesAndSuccessed
{

    private $from_date;
    private $to_date;
    private $site_id;
    private $more_than_x_courses;
    private $succeeded;
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
        $this->succeeded = isset($params['succeeded']) ? $params['succeeded'] : null;
        $this->count = isset($params['count']) ? $params['count'] : null;
        $this->paginate = isset($params['paginate']) ? $params['paginate'] : null;

        $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;

        $this->exprt_type = isset($params['exprt_type']) ? $params['exprt_type'] : 'csv';
    }

    public function collectQuery()
    {
        /* users_results : view in db */
        return DB::table('users_results')
          ->join('members','members.id','users_results.user_id')
          ->when(! empty($this->site_id), function($query) {
              return $query->whereIn('users_results.site_id', $this->site_id);
          })
          ->when($this->succeeded, function($query) {
              return $query->where('users_results.max_degree', '>=', pointOfSuccess()); // '50:00'
          })
          ->whereNull('members.error_email')
          ->where('users_results.max_created_at' ,'>=',  date('Y-m-d', strtotime($this->from_date)) )
          ->where('users_results.max_created_at' ,'<=',  date('Y-m-d', strtotime($this->to_date)) )
          ->select('members.id','members.email','members.name','members.whats_app', DB::raw('count(users_results.user_id)'))
          ->groupBy(['users_results.user_id'])
          ->orderBy('members.id')
          ->having(DB::raw('count(users_results.user_id)'), '=', $this->more_than_x_courses);

    }

    public function getData()
    {

          $q = $this->collectQuery();
          if ($this->paginate){
            return $q->paginate($this->paginate);
          }
          return $q->get();

          // $site_id = isset($params['site_id']) ? $params['site_id'] : [];
          //
          // $from_date = isset($params['from_date']) ? $params['from_date'] : null;
          // $to_date = isset($params['to_date']) ? $params['to_date'] : null;
          //
          // $count = isset($params['count']) ? $params['count'] : null;
          // $paginate = isset($params['paginate']) ? $params['paginate'] : null;

          /* users_results : view in db */
          // $q = DB::table('users_results')
          //   ->join('members','members.id','users_results.user_id')
          //   ->when(! empty($site_id), function($query) use($site_id){
          //       return $query->whereIn('users_results.site_id', $site_id);
          //   })
          //   ->when($succeeded, function($query) use($succeeded){
          //       return $query->where('users_results.max_degree', '>=', pointOfSuccess()); // '50:00'
          //   })
          //   ->where('users_results.max_created_at' ,'>=',  date('Y-m-d', strtotime($from_date)) )
          //   ->where('users_results.max_created_at' ,'<=',  date('Y-m-d', strtotime($to_date)) )
          //   ->select('members.id','members.email','members.name','members.whats_app', DB::raw('count(users_results.user_id)'))
          //   ->groupBy(['users_results.user_id'])
          //   ->having(DB::raw('count(users_results.user_id)'), '=', $more_than_x_courses);
          //
          // if ($count){ return $q->count(); }
          // if ($paginate){ return $q->paginate($paginate); }
          // return $q->get();

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

      // /* users_results : view in db */
      // $q = DB::table('users_results')
      //   ->join('members','members.id','users_results.user_id')
      //
      //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
      //         $join->on('emails_to_send_queries_members.user_id','members.id')
      //         ->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      //   })
      //   ->whereNull('emails_to_send_queries_members.id')
      //
      //   ->when(! empty($site_id), function($query) use($site_id){
      //       return $query->whereIn('users_results.site_id', $site_id);
      //   })
      //   ->when($succeeded, function($query) use($succeeded){
      //       return $query->where('users_results.max_degree', '>=', pointOfSuccess()); // '50:00'
      //   })
      //   ->where('users_results.max_created_at' ,'>=',  date('Y-m-d', strtotime($from_date)) )
      //   ->where('users_results.max_created_at' ,'<=',  date('Y-m-d', strtotime($to_date)) )
      //   ->select('members.id','members.email','members.name','members.whats_app', DB::raw('count(users_results.user_id)'))
      //   ->groupBy(['users_results.user_id'])
      //   ->having(DB::raw('count(users_results.user_id)'), '=', $more_than_x_courses)->first();


      // $q = DB::table('users_results')
      //   ->join('members','members.id','users_results.user_id')
      //
      //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
      //         $join->on('emails_to_send_queries_members.user_id','members.id')
      //         ->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      //   })
      //   ->whereNull('emails_to_send_queries_members.id')
      //
      //   ->where('users_results.site_id',  $site_id )
      //   ->where('users_results.max_degree', '>=' , '50:00' )
      //   ->select('members.id','members.email','members.name','members.whats_app',DB::raw('count(users_results.user_id)'))
      //   ->groupBy(['users_results.user_id'])
      //   ->having(DB::raw('count(users_results.user_id)'), '=', $more_than_x_courses)->first();


      // return $q;

    }


    public function AssignNotificationsToUser($notification_id,$params=[])
    {

      $q = $this->collectQuery();
      if (! $q){
        return [];
      }

      $notificationId = $notification_id;

      // same query as getData() above
      $q->orderBy('id')->chunk(50, function ($items) use($notificationId) {
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
          //
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

          return true;
    }

}
