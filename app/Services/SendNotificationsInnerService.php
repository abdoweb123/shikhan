<?php

namespace App\Services;
use Illuminate\Http\Request;
use DB;

class SendNotificationsInnerService
{

  public function sendAfterRegisterNotification($user_id, $data)
  {
      // insert notifications then assign to inserted Notificateion to user
      $insertedNotificateion = DB::Table('notifications_inner')->insertGetId([
          'title' => $data['title'],
          'tag' => 'after_registration_notification',
          'body' => $data['message'],
          'created_at' => now()
      ]);

      DB::table('notifications_inner_members')->insert([
        'user_id' => $user_id,
        'notification_id' => $insertedNotificateion
      ]);

  }



}
