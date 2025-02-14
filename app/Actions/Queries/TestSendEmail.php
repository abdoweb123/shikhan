<?php

namespace App\Actions\Queries;
use DB;

class TestSendEmail
{
  public function getData($params=[])
  {
      $count = isset($params['count']) ? $params['count'] : null;
      $paginate = isset($params['paginate']) ? $params['paginate'] : null;

      $q = DB::Table('members')
      ->wherein('members.id',['5651','5972','2','5668','5672'])
      ->select('members.id','members.name','members.email');

      if ($count){ return $q->count(); }
      if ($paginate){ return $q->paginate($paginate); }
      return $q->get();
  }

  public function getEmails($params=[])
  {
      $func_id = isset($params['func_id']) ? $params['func_id'] : 0;

      return DB::Table('members')
      ->wherein('members.id',['5651','5972','2','5668','5672'])
      ->leftjoin('emails_to_send_queries_members', function ($join) use($func_id) {
            $join->on('emails_to_send_queries_members.user_id','members.id')->where('emails_to_send_queries_members.emails_to_send_queries_id',$func_id);
      })
      ->whereNull('emails_to_send_queries_members.id')
      ->select('members.id','members.name','members.email')
      ->first();
  }

  public function AssignNotificationsToUser($notification_id,$params=[])
  {

        $notificationId = $notification_id;

        // same query as getData() above
        DB::Table('members')
        ->wherein('members.id',['5651','5972','2','5668','5672','5671','5663','5842']) // '5663','5842' dr abdullah
        ->select('members.id')
        ->orderBy('id')->chunk(50, function ($items) use($notificationId) {
              $inserts = [];
              foreach ($items as $item) {
                  $inserts[] = [
                    'user_id' => $item->id,
                    'notification_id' => $notificationId,
                  ];
              }
              DB::table('notifications_inner_members')->insert($inserts);
        });

        return true;
  }

}
