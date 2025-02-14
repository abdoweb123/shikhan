<?php

namespace App\Services;
use App\PrizeUserOutside;

class ExtraPointsService
{
  private $user;
  private $course;

  public function __construct( $params = [] )
  {
      $this->user = isset($params['user']) ? $params['user'] : null;
      $this->course = isset($params['course']) ? $params['course'] : null;
  }

  public function calculateExtraPonits()
  {
      if($this->userAttendeZoom()){
        return PrizeUserOutside::ADDED_POINTS;
      }
      return 0;
  }

  public function userAttendeZoom()
  {
      return PrizeUserOutside::where('user_id', $this->user->id)->where('course_id', $this->course->id)->exists();
  }

}
