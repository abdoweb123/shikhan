<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\PartnerService;
use App\Services\PayPartnerService;
use App\Services\CurrencyService;
use App\helpers\UtilHelper;
use Auth;
use DB;
use App\member;


class PayController extends Controller
{

    public function __construct(private PartnerService $partnerService, private PayPartnerService $payPartnerService, private CurrencyService $currencyService)
    {

    }

    public function review(Request $request)
    {

        $request->flash();

        $data['get'] = $request->input();
        // $data['result'] = collect([]);
        // $data['change_to_free_status'] = collect([]);
        $data['change_to_free_status'] = $this->payPartnerService->getChangeToFreeStatus();
        $data['partners'] = $this->partnerService->getAll();
        $data['currencies'] = $this->currencyService->getAll();


        $data['result'] = \App\member::select('members.id','name','email','partner_id','phone','pay_image','pay_amount','discount','free_status','status','currency_id','created_at');

        if (!empty($data['get']['term'])) {
          $data['result'] = $data['result']->where('email','like','%'.$data['get']['term'].'%')
            ->orwhere('name','like','%'.$data['get']['term'].'%')
            ->orwhere('phone','like','%'.$data['get']['term'].'%');
        }


        if (!empty($data['get']['search_free_status']) && $data['get']['search_free_status'] != 'all') {
          $data['result'] = $data['result']->where('free_status', $data['get']['search_free_status']);
        }

        if (!empty($data['get']['search_partner_id']) && $data['get']['search_partner_id'] != 'all') {
          $data['result'] = $data['result']->where('search_partner_id', $data['get']['search_partner_id']);
        }






        if (empty($data['get']['orderby'])){
            $data['result'] = $data['result']->orderBy('members.created_at', 'DESC');
        } elseif ($data['get']['orderby'] == 1) {
            $data['result'] = $data['result']->orderBy('members.created_at', 'DESC');
        } elseif ($data['get']['orderby'] == 2) {
            $data['result'] = $data['result']->orderBy('members.created_at', 'ASC');
        } elseif ($data['get']['orderby'] == 3) {
            $data['result'] = $data['result']->orderBy('members.name', 'ASC');
        }


        $data['result'] = $data['result']->paginate(50);



        return view ('back.content.pay.review', $data);


    }

    public function reviewUpdate(Request $request)
    {

        $member = member::where('id', $request->id)->firstorfail();
        $this->payPartnerService->updateFreeStatusData($member, $request->partner_id ,$request->free_status, $request->pay_amount,  $request->currency_id);

        return back()->with('success', 'Member Updated Successfully!');
    }





}
