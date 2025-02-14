<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\LessonOld;

use App;
use Auth;

use DB;

class SendNotificationsInnerController extends Controller
{
    public function index(Request $request)
    {

        // return response()->json(['data'=>$request->params['id'] ]);

        $data['allNotifications'] = DB::Table('notifications_inner')
          ->leftjoin('notifications_inner_members','notification_id','notifications_inner.id')
          ->where('notifications_inner.is_active',1)
          ->where('notifications_inner_members.user_id',Auth::guard('web')->id())
          ->where('notifications_inner_members.is_active',1)
          ->orwhere('notifications_inner.for_all', 1)
          ->orderBy('notifications_inner_members.seen_at')
          ->orderBy('notifications_inner.created_at','desc')
          ->select('notifications_inner.id','notifications_inner.title','notifications_inner_members.seen_at','notifications_inner.created_at')
          ->get();

          $getNotificationId = $request->id;
          if ($request->params){ // if came from ajax to get details
            $getNotificationId = $request->params['id'];
          }

          if($getNotificationId){
            $data['currentNotification'] = DB::Table('notifications_inner')
              ->leftjoin('notifications_inner_members','notification_id','notifications_inner.id')
              ->where('notifications_inner.is_active',1)
              ->where('notifications_inner_members.user_id',Auth::guard('web')->id())
              ->where('notifications_inner_members.is_active',1)
              ->where('notifications_inner.id',  $getNotificationId)
              ->orwhere( function($query) use($getNotificationId) {
                  return $query->where('notifications_inner.for_all', 1)->where('notifications_inner.id',  $getNotificationId);
              })
              ->select('notifications_inner.id','notifications_inner.title','notifications_inner.body')
              ->first();

            DB::Table('notifications_inner_members')
              ->where('user_id',Auth::guard('web')->id())
              ->where('notification_id',$getNotificationId)
              ->update(['seen_at'=>now()]);
          }

          if ($request->params && $request->params['details_only'] == true){
              return response()->json([ 'data' => $data['currentNotification'] ]);
          }


          return view('front.content.notifications_inner.index',$data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|integer',
        ]);

        DB::Table('notifications_inner_members')
          ->where('user_id', auth()->id())
          ->where('notification_id', $request->notification_id)
          ->update(['seen_at' => now()]);

        return response()->json(['status' => 1]);
    }
}
