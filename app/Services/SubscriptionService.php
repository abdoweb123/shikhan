<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use DB;

class SubscriptionService
{

  private $siteId;

  public function whereSiteId($siteId)
  {
      $this->siteId = $siteId;
      return $this;
  }

  public function from($from)
  {
      $this->from = $from;
      return $this;
  }

  public function getFrom()
  {
      return $this->from ?? date("Y-m-d") . " 00:00:00";
  }

  public function to($to)
  {
      $this->to = $to;
      return $this;
  }

  public function getTo()
  {
      return $this->from ?? date("Y-m-d") . " 23:59:59";
  }

  public function getSiteSubscriptionsCountOfPeriod()
  {

      $siteId = $this->siteId;
      $from = $this->getFrom();
      $to = $this->getTo();

      return DB::select("Select count(*) as total FROM `site_subscriptions`
            WHERE site_id = $siteId
            and created_at >= '$from' and created_at <= '$to'
          ");
  }

  public function store()
  {

      try {
        $siteFlag = \App\site::where('id', $this->siteId)->value('new_flag');

        if ($siteFlag === null){ // because new_flag in db mybe 0
          return;
        }

        $date = date("Y-m-d", strtotime($this->getFrom()));

        DB::table('members_sites_subscriptions')
          ->updateOrInsert(
              ['date' => $date, 'site_id' => $this->siteId],
              ['total' => $this->getSiteSubscriptionsCountOfPeriod()[0]->total, 'site_new_flag' => $siteFlag]
          );
      } catch (\Exception $ex) {
        \Illuminate\Support\Facades\Log::emergency($ex->getMessage());
      }

  }


}
