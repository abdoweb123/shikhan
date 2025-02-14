<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\Content;
use App\User;
use App\Models\Lecture;
use App\Models\Subscription;
use App\Services\LectureService;
use App\helpers\UtilHelper;
use Auth;

class LectureController extends Controller
{






  public function subscribe(Request $request)
  {
        // dd($user_details);
        $billing_data = [
                "apartment" =>'1',
                "email" => "admin@arabiceasily.com",
                "floor" => "1",
                "first_name" => "Tarek",
                "street" => "naser city",
                "building" => "4137",
                "phone_number" =>  "01098765463",
                "city" => "Cairo",
                "country" => "Egypt",
                "last_name" => "Mustafa",
              ];

        $paymob = new PaymobController();

        $order_id = 'l'.$lecture->id.'u'.$user_details->id.'t'.strtotime(now());

        // check payment here
        $this->share([ 'page' => Lecture::PAGE]);
        $this->seoInfo('home','');
        return $paymob->createPayRequest($order_id,$billing_data,$lecture);
        // if success continu

        Subscription::create([ 'user_id' => 1 , 'lecture_id' => 1 , 'ip' => UtilHelper::getUserIp() ]);

        // success
        $this->flashAlert([ 'success' => ['msg'=> __('messages.success_subscribed')] ]);

       return back();

  }

  public function subscribtionOf($user_id)
  {
      return Subscription::ofUser( $user_id )->first();
  }

}
