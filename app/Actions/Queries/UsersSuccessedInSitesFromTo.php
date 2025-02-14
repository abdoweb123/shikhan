<?php

namespace App\Actions\Queries;
use DB;

// طلاب نجحو فى دبلوم / ات من الى
class UsersSuccessedInSitesFromTo
{

    private $from_date;
    private $to_date;
    private $site_id;
    private $count;
    private $paginate;
    private $func_id;
    private $exprt_type;

    public function __construct( $params=[] )
    {
        $this->from_date = isset($params['from_date']) ? $params['from_date'] : null;
        $this->to_date = isset($params['to_date']) ? $params['to_date'] : null;
        $this->site_id = isset($params['site_id']) ? $params['site_id'] : [];
        $this->count = isset($params['count']) ? $params['count'] : null;
        $this->paginate = isset($params['paginate']) ? $params['paginate'] : null;

        $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;

        $this->exprt_type = isset($params['exprt_type']) ? $params['exprt_type'] : 'csv';
    }

    public function collectQuery()
    {

        return DB::Table('members')
            ->join('member_sites_results', 'member_sites_results.user_id', 'members.id')
            ->where('member_sites_results.user_successed', 1)
            ->whereIn('member_sites_results.site_id', $this->site_id)
            ->where('member_sites_results.user_max_test_datetime', '>=', date('Y-m-d 00:00:00', strtotime($this->from_date)) )
            ->where('member_sites_results.user_max_test_datetime', '<=', date('Y-m-d 23:59:59', strtotime($this->to_date)) )
            ->whereNull('members.error_email')

            ->orderBy('members.id')
            ->select('members.id','members.name','members.email')
            ->distinct('members.id')
            ;
    }

    public function getData()
    {

        $q = $this->collectQuery();
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

        // $from_date = isset($params['from_date']) ? $params['from_date'] : null;
        // $to_date = isset($params['to_date']) ? $params['to_date'] : null;
        // $site_id = isset($params['site_id']) ? $params['site_id'] : [];
        // $func_id = isset($params['func_id']) ? $params['func_id'] : null;


        $q = $this->collectQuery();

        return $q->leftjoin('emails_to_send_queries_members', function ($join) {
                $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id', $this->func_id);
        })
        ->whereNull('emails_to_send_queries_members.id')->first();



        // $q = DB::Table('members')
        //     ->join('site_subscriptions', 'site_subscriptions.user_id', 'members.id')
        //     ->leftjoin('course_tests_results', function ($join) use($site_id) {
        //           $join->on('course_tests_results.user_id', 'members.id')
        //           ->when(! empty($site_id), function($q) use($site_id){
        //               return $q->whereIn('course_tests_results.site_id', $site_id);
        //           });
        //     })
        //     ->when(! empty($site_id), function($q) use($site_id){
        //         return $q->whereIn('site_subscriptions.site_id', $site_id);
        //     })
        //     ->where('site_subscriptions.created_at', '>=', date('Y-m-d', strtotime($from_date)) )
        //     ->where('site_subscriptions.created_at', '<=', date('Y-m-d', strtotime($to_date)) )
        //     ->wherenull('course_tests_results.id')
        //
        //     ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
        //             $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
        //     })
        //     ->whereNull('emails_to_send_queries_members.id')
        //
        //     ->groupBy('members.id')
        //     ->select('members.id','members.name','members.email')
        //     ->first();


        // $coursesOfSite = DB::table('courses')->join('course_site','courses.id','course_site.course_id')
        //   ->where('course_site.site_id',  $site_id )->pluck('courses.id');

        // $q = DB::Table('members')
        //     ->join('course_subscriptions', function ($join) use($coursesOfSite,$from_date) {
        //         $join->on('course_subscriptions.user_id','members.id')
        //           ->wherein('course_subscriptions.course_id', $coursesOfSite)
        //           ->where('course_subscriptions.created_at' ,'>=', date($from_date));
        //     })
        //     ->wherenotin('members.id', DB::table('course_tests_results')->wherein('course_id',$coursesOfSite)->pluck('user_id'))
        //
        //     ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
        //           $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
        //     })
        //     ->whereNull('emails_to_send_queries_members.id')
        //
        //     ->groupBy('members.id')
        //     ->select('members.id','members.name','members.email')
        //     ->first();

        // return $q;

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

    }

}
