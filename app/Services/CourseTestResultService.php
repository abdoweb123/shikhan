<?php

namespace App\Services;
// use Illuminate\Support\Facades\DB;
use App\course_test_result;
use Auth;

class CourseTestResultService
{
  public $minutesToOpenTest=120;

  public function getLastUserTestSince($course_id, $user_id)
  {
      if (! Auth::guard('web')->user()) {
        return null;
      }

      $courseTestSince = null;
      $courseTestResult = course_test_result::where('user_id',$user_id)
        // ->where('course_id',$course_id)
        ->select('created_at')->latest()->first();
      if ($courseTestResult){
        $courseTestSince = $courseTestResult->created_at->diffInSeconds(date('Y-m-d H:i:s'));
      }

      return $courseTestSince;
  }

  public function UserCanOpenTest($course_id, $user_id)
  {
      // return true;
      $lastUserTestSince = $this->getLastUserTestSince($course_id, $user_id);

      if (!$lastUserTestSince){ // test for the first time
        return true;
      }
      if ($lastUserTestSince > $this->minutesToOpenTest * 60){
        return true;
      }
      return false;
  }

  public function getRemainingPeriodToOpenTest($lastUserTestSince)
  {
      //
  }

}
