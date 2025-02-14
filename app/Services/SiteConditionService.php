<?php

namespace App\Services;
use App\Services\GlobalService;

class SiteConditionService
{
  private $site;
  private $user;
  private $globalService;

  public function __construct()
  {
      $this->globalService = new GlobalService();
  }

  public function setSite($site)
  {
      $this->site = $site;
      return $this;
  }

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function getUserStatusOfSiteDependents()
  {
      $return = new \stdClass();
      $return->userFinishDependents = false;
      $return->siteDependentsTitles = '';

      $sitesMustFinishToSubscribe = $this->globalService->getSitesMustFinishToSubscribeIn($this->site->id);
      if ( $sitesMustFinishToSubscribe->isNotEmpty() ) {
            $return->userFinishDependents = $this->user->isSuccessedInSites($sitesMustFinishToSubscribe); // 01 replaced $this->globalService->userFinishDependents($this->user, $sitesMustFinishToSubscribe );
            $return->siteDependentsTitles = implode("-", $sitesMustFinishToSubscribe->pluck('name')->toArray() );
      }

      return $return;

  }

  public function getUserSiteConditionsDetails()
  {

      $return = new \stdClass();
      $return->condition1Status = collect([]);
      $return->condition2Status = collect([]);

      if($this->site->hasCondidtion1()){
          // site has condition of "finish at least one site"
          $return->condition1Status = $this->user->isAlreadySuccessedInSite($this->site->id);
      }

      if($this->site->hasCondidtion2()){
          // user must finishied dependents courses to can subscribe
          $return->condition2Status = $this->getUserStatusOfSiteDependents();
      }

      return $return;

  }






}
