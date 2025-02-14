<?php

namespace App\Actions\Queries;
use DB;

// الطبلاب الذين لم يختبرو نهائيا من الى
class UsersDidntTestedFromTo
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
        return DB::table('members')
          ->leftjoin('course_tests_results', function ($join) {
              $join->on('members.id', '=', 'course_tests_results.user_id')
                ->where('course_tests_results.created_at' ,'>=',  date('Y-m-d', strtotime($this->from_date)) )
                ->where('course_tests_results.created_at' ,'<=',  date('Y-m-d', strtotime($this->to_date)) )
                ->when(! empty($this->site_id), function ($q) {
                    return $q->whereIn('course_tests_results.site_id', $this->site_id);
                });
          })
          ->whereNull('course_tests_results.id')
          ->whereNull('members.error_email')
          ->orderBy('members.id')
          ->select('members.id','members.name','members.email','members.phone','members.whats_app');
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

        $q = $this->collectQuery();

        return $q->leftjoin('emails_to_send_queries_members', function ($join) {
                $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id', $this->func_id);
        })
        ->whereNull('emails_to_send_queries_members.id')->first();


        // $q = DB::table('members')
        //   ->leftjoin('course_tests_results', function ($join) use($from_date, $to_date, $site_id){
        //       $join->on('members.id', '=', 'course_tests_results.user_id')
        //         ->where('course_tests_results.created_at' ,'>=',  date('Y-m-d', strtotime($from_date)) )
        //         ->where('course_tests_results.created_at' ,'<=',  date('Y-m-d', strtotime($to_date)) )
        //         ->when(! empty($site_id), function ($q) use($site_id) {
        //             return $q->whereIn('course_tests_results.site_id', $site_id);
        //         });
        //   })
        //   ->whereNull('course_tests_results.id')
        //
        //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
        //           $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
        //   })
        //   ->whereNull('emails_to_send_queries_members.id')
        //   ->select('members.id','members.email')->first();

          // $q = DB::table('members')
          //   ->leftjoin('course_tests_results', function ($join) use($from_date, $to_date){
          //       $join->on('members.id', '=', 'course_tests_results.user_id')
          //         ->where('course_tests_results.created_at' ,'>=', date($from_date)) // '2021-12-20'
          //         ->where('course_tests_results.created_at' ,'<=', date($to_date)) // '2021-12-21'
          //         ;
          //   })
          //   ->whereNull('course_tests_results.id')
          //
          //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
          //         $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
          //   })
          //   ->whereNull('emails_to_send_queries_members.id')
          //   ->select('members.id','members.email')->first();

          return $q;

    }

    public function AssignNotificationsToUser($notification_id,$params=[])
    {

          $notificationId = $notification_id;

          // $count = isset($params['count']) ? $params['count'] : null;
          // $paginate = isset($params['paginate']) ? $params['paginate'] : null;
          $from_date = isset($params['from_date']) ? $params['from_date'] : null;
          $to_date = isset($params['to_date']) ? $params['to_date'] : null;

          // same query as getData() above
          DB::table('members')
            ->leftjoin('course_tests_results', function ($join) use($from_date, $to_date){
                $join->on('members.id', '=', 'course_tests_results.user_id')
                  ->where('course_tests_results.created_at' ,'>=', date($from_date)) // '2021-12-20'
                  ->where('course_tests_results.created_at' ,'<=', date($to_date)) // '2021-12-21'
                  ;
            })
            ->whereNull('course_tests_results.id')
            ->select('members.id')
            ->orderBy('id')->chunk(50, function ($items) use($notificationId) {
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
