<?php

namespace App\Traits;

trait Fcm
{
    public function sendFcm($mobile_type,$fcm_token,$data)
    {
        $tokens = [];
        $apns_ids = [];
        $responseData = [];
        // $data= $request->all();


        // for Android
            // foreach ($FCMTokenData as $key => $value) {
            //   $tokens[] = $value->token;
            // }
            // define('YOUR_SERVER_KEY', 'YOUR_SERVER_KEY' );

            // $msg = array (
            //   'message'  => 'body Test from mandoob',
            //   'title' => 'Notification',
            //   // 'subtitle' => 'This is a subtitle',
            // );


            // android
            if ($mobile_type == 'android') {
                $fields = array (
                  'to' => $fcm_token,
                  'data' => $data,
                  // 'registration_ids'  => $data->fcm_token,
                  // 'content_available' => true,
                  // 'priority' => 'high',
                  // 'notification'  => $msg,
                  // 'time_to_live' => '2419200'
                );
            }

            // ios
            if ($mobile_type == 'ios') {
                $title = $data['title'];
                $body = $data['body'];
                $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');

                $fields = array (
                  'to' => $fcm_token,
                  'notification' => $notification,
                  // 'registration_ids'  => $data->fcm_token,
                  // 'content_available' => true,
                  'priority' => 'high',
                  // 'notification'  => $msg,
                  // 'time_to_live' => '2419200'
                );
            }


            $headers = array (
              'Authorization: key=' . 'AAAAhKceMeQ:APA91bHt0ftxkFeXrAdHfzvgR-9vhKik_pjJYNgdyziAM-px86iaG022x-LNCnUA1c3uu7sHKx0uCjekVKZRY5sEAw3ysECHAmMALfzyfB1v4INp3ixsulmO3oSZTzyMlIKNFmRHEA4d',
              'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, CURL_IPRESOLVE_V4 ); // false
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch);

            if ($result === FALSE) {
              // return curl_error($ch);
              return false;
            }

            $result = json_decode($result,true);
            // $responseData['android'] = [
            //   "result" =>$result
            // ];
            $responseData = [
              "result" =>$result
            ];
            curl_close( $ch );


        // for IOS
        // if ($FCMTokenData = $this->fcmToken->whereIn('user_id',$users)->where('apns_id','!=',null)->select('apns_id')->get()) {
        //     foreach ($FCMTokenData as $key => $value)
        //     {
        //     $apns_ids[] = $value->apns_id;
        //     }
        //     $url = "https://fcm.googleapis.com/fcm/send";
        //     $serverKey = 'YOUR_SERVER_KEY';
        //     $title = "Thsi is title";
        //     $body = 'This is body';
        //     $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
        //     $arrayToSend = array('registration_ids' => $apns_ids, 'notification' => $notification,'priority'=>'high');
        //     $json = json_encode($arrayToSend);
        //     $headers = array();
        //     $headers[] = 'Content-Type: application/json';
        //     $headers[] = 'Authorization: key='. $serverKey;
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     //Send the request
        //     $result = curl_exec($ch);
        //     if ($result === FALSE) {
        //       die('FCM Send Error: ' . curl_error($ch));
        //     }
        //     $result = json_decode($result,true);
        //     $responseData['ios'] =[
        //     "result" =>$result
        //     ];
        //     //Close request
        //     curl_close($ch);
        // }


        return $responseData;
    }

    public function sendFcmWeb()
    {


      $headers = array (
        'Authorization: Bearer ' . 'BFoAOpmoGbIqIL095sPAwy2z4MOG79XTTsC0MED_zHqh4RxTdwY3e5YnDeZO83WdwciPQkcqi7GtplLf3Tqo2_k',
        'Content-Type: application/json'
      );

      $fields =  ["message" => [
                  "notification"=> [
                    "title"=> "FCM Message",
                    "body"=> "This is a message from FCM"
                  ],
                  "webpush"=> [
                    "headers"=> [
                      "Urgency"=> "high"
                    ],
                    "notification"=> [
                      "body"=> "This is a message from FCM to web",
                      "requireInteraction"=> "true",
                      "badge"=> "/badge-icon.png"
                    ]
                  ]
                ],
                "token"=> "cjSAzc3tkHQ:APA91bH8L-q_aP05GvEPQ0LSkVzVwi4kOq3xKeTQ08ZtEvorgufH1l1OigJZ3ZKBkIlCZPLwzOSAZBpqlBFz1CDXwYPVjrH_073T2udC15-B-FsFneVgQIc8EveKAmXmoFXl9fPcVUmW"
              ];



      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/yakfeekapp-a65fc/messages:send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, CURL_IPRESOLVE_V4 ); // false
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
      $result = curl_exec($ch);

      if ($result === FALSE) {
        return curl_error($ch);
      }

      $result = json_decode($result,true);
      $responseData['web'] = [
        "result" =>$result
      ];
      curl_close( $ch );

      return $responseData;

        //   curl -X POST -H "Authorization: Bearer ya29.ElqKBGN2Ri_Uz...PbJ_uNasm" -H "Content-Type: application/json" -d '{
        //   "message": {
        //     "notification": {
        //       "title": "FCM Message",
        //       "body": "This is a message from FCM"
        //     },
        //     "webpush": {
        //       "headers": {
        //         "Urgency": "high"
        //       },
        //       "notification": {
        //         "body": "This is a message from FCM to web",
        //         "requireInteraction": "true",
        //         "badge": "/badge-icon.png"
        //       }
        //     }
        //   },
        //   "token": "bk3RNwTe3H0:CI2k_HHwgIpoDKCIZvvDMExUdFQ3P1..."
        //   }
        // }' "https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send"

    }

}
