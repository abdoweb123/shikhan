<?php

namespace App\Services;

// use App\helpers\UtilHelper;
use App\member;

class PayPartnerService
{

  public function getChangeToFreeStatus()
  {
    return [
        [
          'id' => member::NOT_PAID,
          'title' => __('trans.free_status_'.member::NOT_PAID),
        ],
        [
          'id' => member::PAID,
          'title' => __('trans.free_status_'.member::PAID),
        ],
        [
          'id' => member::FREE,
          'title' => __('trans.free_status_'.member::FREE),
        ]
      ];
  }


  public function updateFreeStatusData( $model, $partner_id, $status, $pay_amount, $currency_id )
  {

      $model->pay_amount = $pay_amount;
      $model->partner_id = $partner_id;
      $model->free_status = $status;
      $model->currency_id = $currency_id;

      $model->save();

      return true;
  }

}
