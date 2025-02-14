<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\User;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Comment;
use App\helpers\UtilHelper;
use App\Traits\Fcm;

class NotificationService
{
  use Fcm;

  public function notifyLike( $to = [] , $params = [] )
  {

      $user_sender_id = $params['user_sender_id'];

      // get user sender
      $user_sender = User::where('id',$user_sender_id)->select('id','name')->first();
      // get current item
      $item = Item::where('id',$params['item_id'])->select('id','user_id')->first();
      // get woner of item
      $user_receiver = User::where('id',$item->user_id)->select('id','name','fcm_token','mobile_type')->first();




      // prepare for fcm
      $msg['user_sender'] = $user_sender_id;
      $msg['user_reciever'] = $user_receiver->id;
      $msg['item'] = $item->id;
      $msg['title'] = __('messages.new_like');
      $msg['body'] = __('messages.likes_your_post' , [ 'user_name' => $user_sender->name ] ) ;



      // fcm
      if ( in_array( 'fcm' , $to ) ) {
          $response = $this->notifyFcm($user_receiver,$msg);
          if ( $response === false ){}
          // if ($response['result']['failure'] == 1 ){
          //   return false;
          // }
      }


      // db
      if ( in_array( 'db' , $to ) ) {
          $data['user_sender_id'] = $user_sender_id;
          $data['user_reciever_id'] = $user_receiver->id;
          $data['table_name'] = 'items';
          $data['table_id'] = $item->id;
          $data['type'] = 1; // like , 2 comment
          $data['data'] = __('messages.likes_your_post' , [ 'user_name' => $user_sender->name ] );
          $this->store($data);
      }


      // web
      if ( in_array( 'web' , $to ) ) {
          event(new \App\Events\MainNotification(
              $user_receiver->id,
              $user_sender->name,
              '1',
              route('items.show' , ['id' => $item->id ]),
              __('messages.likes_your_post' , [ 'user_name' => $user_sender->name ] ) . ' : ' . UtilHelper::currentDate()
            )
          );
      }


  }

  public function notifyComment( $to = [] , $params = [] )
  {

      $user_sender_id = $params['user_sender_id'];


      // get user sender
      $user_sender = User::where('id',$user_sender_id)->select('id','name')->first();
      // get current item
      $item = Item::where('id',$params['item_id'])->select('id','user_id')->first();
      // get woner of item
      $user_receiver = User::where('id',$item->user_id)->select('id','name','fcm_token','mobile_type')->first();


      $parent_Comment_user = 0;
      if ($params['parent_id'] != 0) {
        $parentComment = Comment::where('id',$params['parent_id'])->select('user_id')->first();
        if ($parentComment) {
          $parent_Comment_user = $parentComment->user_id;
        }
      }






      // fcm
      if ( in_array( 'fcm' , $to ) ) {
          // send to post woner
          $msg['user_sender'] = $user_sender_id;
          $msg['user_reciever'] = $user_receiver->id;
          $msg['item'] = $item->id;
          $msg['title'] = __('messages.new_comment');
          $msg['body'] = __('messages.comments_your_post' , [ 'user_name' => $user_sender->name ]) ;
          $response = $this->notifyFcm($user_receiver,$msg);
          if ( $response === false ){}


          // send to parent comment woner
          if ($parent_Comment_user != 0) {
            $msg['user_sender'] = $user_sender_id;
            $msg['user_reciever'] = $parent_Comment_user; // parent comment woner
            $msg['item'] = $item->id;
            $msg['title'] = __('messages.new_comment');
            $msg['body'] = __('messages.comments_your_comment' , [ 'user_name' => $user_sender->name ]) ;
            $response = $this->notifyFcm($user_receiver,$msg);
            if ( $response === false ){}
          }

      }


      // db
      if ( in_array( 'db' , $to ) ) {
          $data['user_sender_id'] = $user_sender_id;
          $data['user_reciever_id'] = $user_receiver->id;
          $data['table_name'] = 'items';
          $data['table_id'] = $item->id;
          $data['type'] = 2; // like , 2 comment
          $data['data'] = __('messages.comments_your_post' , [ 'user_name' => $user_sender->name ]);
          $this->store($data);

          // send to parent comment woner
          if ($parent_Comment_user != 0) {
            $data['user_sender_id'] = $user_sender_id;
            $data['user_reciever_id'] = $parent_Comment_user; // parent comment woner
            $data['table_name'] = 'items';
            $data['table_id'] = $item->id;
            $data['type'] = 2; // like , 2 comment
            $data['data'] = __('messages.comments_your_comment' , [ 'user_name' => $user_sender->name ]);
            $this->store($data);
          }
      }


      // web
      if ( in_array( 'web' , $to ) ) {
          event(new \App\Events\MainNotification(
              $user_receiver->id,
              $user_sender->name,
              '2',
              route('items.show' , ['id' => $item->id ]),
              __('messages.comments_your_post' , [ 'user_name' => $user_sender->name ] ) . ' : ' . UtilHelper::currentDate()
            )
          );

          // send to parent comment woner
          if ($parent_Comment_user != 0) {
            event(new \App\Events\MainNotification(
                $parent_Comment_user,
                $user_sender->name,
                '2',
                route('items.show' , ['id' => $item->id ]),
                __('messages.comments_your_comment' , [ 'user_name' => $user_sender->name ] ) . ' : ' . UtilHelper::currentDate()
              )
            );
          }
      }


  }






  public function notifyFcm($user,$data)
  {

      $validate = $this->validateFcmSend($user,$data);
      if ($validate !== true) {
        return $validate;
      }

      return $this->sendFcm($user->mobile_type,$user->fcm_token,$data);

  }

  public function validateFcmSend($user,$data)
  {

      if (! $user) {
        return 'Select User';
      }

      if (! $user->fcm_token ) {
        return 'Token Not Found';
      }

      if (! $user->mobile_type ) {
        return 'No Mobile Type Found';
      }

      return true;

  }

  public function store($data)
  {
    // store notification in db
    Notification::Create($data);
  }



  public function getNotificationByUserId($userId)
  {
    return Notification::with('user_sender','item_type')->where('user_reciever_id',$userId)->orderby('id','desc')->paginate(10);
  }

  public function getUnreadNotificationByUserId($userId)
  {
    return Notification::with('user_sender','item_type')->where('user_reciever_id',$userId)->Unread()->orderby('id','desc')->paginate(10);
  }



  public function updateReadAt($notificationId)
  {

    $notification = Notification::findOrFail($notificationId);
    $notification->update(['read_at' => UtilHelper::currentDate()]);

    return true;

  }


  public function notifyWeb($id)
  {

    // ??????????????????
    //
    // $user = User::find($id);
    //
    // $data['order_id'] = $order->id;
    // $data['title'] = __('order.status_2');
    // $data['body'] = $order->id . __('order.status_2');

    return $this->sendFcmWeb();

  }


}
