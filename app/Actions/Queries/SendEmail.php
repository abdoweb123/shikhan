<?php

namespace App\Actions\Queries;
use DB;

class SendEmail
{

  private $ids;
  private $func_id;

  public function __construct( $params=[] )
  {
      $this->ids = isset($params['ids']) ? $params['ids'] : [];
      $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;
  }

  public function getEmails()
  {
      return DB::table('emails_to_send')
        ->where('emails_to_send_queries_id', $this->func_id)
        ->first();
  }

  public function AssignNotificationsToUser($notification_id)
  {

        $notificationId = $notification_id;

        // same query as getData() above
        DB::Table('members')
        ->wherein('members.id',$this->ids)
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
