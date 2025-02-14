<?php

namespace App\Services;
use DB;
use App\MemberCoursesResult;

class UserStatisticsServiceStatic
{

  private $user;
  private $site;

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function setSite($site)
  {
      $this->site = $site;
      return $this;
  }

  public function getUserCountTestsInAllRanges()
  {
    $rangeResults = [];

    foreach (courseRateRanges() as $key => $range) {
      $rangeResults[$key] = $this->user->countTestsOfRange( $range, locale: app()->getLocale() );
    }

    return $rangeResults;
  }






}
