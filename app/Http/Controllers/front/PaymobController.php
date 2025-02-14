<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App\Services\PayService;

class PaymobController extends Controller
{

    protected $payService;

    public function __construct(PayService $payService)
    {
        $this->payService = $payService;
    }

    public function pay(Request $request)
    {


        $billing_data = [
              "apartment" =>'2',
              "email" => "info@baldatayiba.com",
              "floor" => "1",
              "first_name" => "Kamal",
              "street" => "106",
              "building" => "9",
              "phone_number" =>  "01140481606",
              "city" => "Cairo",
              "country" => "Egypt",
              "last_name" => "Fahmy",
            ];

        $model = new \stdClass();
        $model->id = 7777;
        $model->title = 'title';

        // $order_id = 'l'.$lecture->id.'u'.$user_details->id.'t'.strtotime(now());
        $order_id = 'donate_'.strtotime(now());

        return $this->payService->createPayRequest($order_id,$billing_data,$model);
        // if success continu

        Subscription::create([ 'user_id' => 1 , 'lecture_id' => 1 , 'ip' => UtilHelper::getUserIp() ]);

        // success
        $this->flashAlert([ 'success' => ['msg'=> __('messages.success_subscribed')] ]);

       return back();


    }


}
