<?php

namespace App\Actions\Queries;
use DB;

// الطبلاب الذين لم يختبرو نهائيا
class UsersDidntTestedEver
{

    private $count;
    private $paginate;
    private $func_id;
    private $exprt_type;

    public function __construct( $params=[] )
    {
        $this->count = isset($params['count']) ? $params['count'] : null;
        $this->paginate = isset($params['paginate']) ? $params['paginate'] : null;

        $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;

        $this->exprt_type = isset($params['exprt_type']) ? $params['exprt_type'] : 'csv';
    }

    public function collectQuery()
    {
          return DB::table('members')
            ->leftjoin('course_tests_results','members.id','course_tests_results.user_id')
            ->whereNull('course_tests_results.id')
            ->whereNull('members.error_email')
            ->orderby('members.id')
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
      //   ->leftjoin('course_tests_results','members.id','course_tests_results.user_id')
      //   ->whereNull('course_tests_results.id')
      //
      //   ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
      //         $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      //   })
      //   ->whereNull('emails_to_send_queries_members.id')
      //   ->select('members.id','members.email')->first();
      //
      //   return $q;

    }


    public function AssignNotificationsToUser($notification_id,$params=[])
    {

          $notificationId = $notification_id;

          // same query as getData() above
          DB::table('members')
            ->leftjoin('course_tests_results','members.id','course_tests_results.user_id')
            ->whereNull('course_tests_results.id')
            ->select('members.id')
            ->orderBy('id')->chunk(50, function ($items) use($notificationId) {
                $inserts = [];
                foreach ($items as $item) {
                    $inserts[] = [
                      'user_id' => $item->id,
                      'notification_id' => $notificationId,
                      'is_active' => 0
                    ];
                }
                DB::table('notifications_inner_members')->insert($inserts);
          });

          return true;
    }
}
