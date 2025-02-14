<?php

namespace App\Actions\Queries;
use DB;

// طلاب انشاو حساب من تاريخ الى تاريخ
class UsersRegisterdFromTo
{


  private $from_date;
  private $to_date;
  private $count;
  private $paginate;
  private $func_id;
  private $exprt_type;

  public function __construct( $params=[] )
  {
      $this->from_date = isset($params['from_date']) ? $params['from_date'] : null;
      $this->to_date = isset($params['to_date']) ? $params['to_date'] : null;
      $this->count = isset($params['count']) ? $params['count'] : null;
      $this->paginate = isset($params['paginate']) ? $params['paginate'] : null;

      $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;

      $this->exprt_type = isset($params['exprt_type']) ? $params['exprt_type'] : 'csv';
  }

  public function collectQuery()
  {
      return DB::Table('members')
          ->where('members.created_at' ,'>=', date($this->from_date))
          ->where('members.created_at' ,'<=', date($this->to_date))
          ->whereNull('members.error_email')
          ->orderBy('members.id')
          ->select('members.id','members.name','members.email');
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

      // $from_date = isset($params['from_date']) ? $params['from_date'] : null;
      // $to_date = isset($params['to_date']) ? $params['to_date'] : null;
      // $func_id = isset($params['func_id']) ? $params['func_id'] : null;
      //
      // $q = DB::Table('members')
      //     ->where('members.created_at' ,'>=', date($from_date))
      //     ->where('members.created_at' ,'<=', date($to_date))
      //     ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
      //           $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      //     })
      //     ->whereNull('emails_to_send_queries_members.id')
      //     ->select('members.id','members.name','members.email')
      //     ->first();
      //
      //     return $q;
  }

  public function AssignNotificationsToUser($notification_id)
  {

        $q = $this->collectQuery();
        if (! $q){
          return [];
        }

        $notificationId = $notification_id;

        // $from_date = isset($params['from_date']) ? $params['from_date'] : null;
        // $to_date = isset($params['to_date']) ? $params['to_date'] : null;

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
  }


}
