<?php

namespace App\Traits;
use DB;

trait UserHasExtraTray
{

  public $countFailedCoursesToGiveTrays = 2; // من رسب عدد 2 دورة فى دبلوم يعطى لها
  public $extraTraysForFailers = 4; // عدد المحاولات التى تعطى له زيادة

  // لفتح ا اختبارات لهم بعد ما استنفذو مرات الاختبار members_extra_trays طلاب يتم اضافتهم الى جدول
  public function userGetXtraTray($user, $course_id)
  {
      return DB::table('members_extra_trays')->where('user_email',$user->email)->where('course_id', $course_id)->exists();
  }

  public function userGetFailedXtraTray($user, $site_id, $course_id)
  {

        $globalService = new \App\Services\GlobalService();

        $userTestsResults = $globalService->getUserCoursesMaxDegrees($user, [ 'siteId' => $site_id ]);
        $userFailedCourses = $globalService->userFailedCourses($userTestsResults);

        $sitCoursesCount = $this->globalService->getActiveCourses([
          'siteId' => $site_id,
          'count' => true
        ]);

        $userFinishedSite = $sitCoursesCount == $userTestsResults->count();
        if (! $userFinishedSite) {
          return false;
        }

        if ( $userFailedCourses->count() > $this->countFailedCoursesToGiveTrays) {
            return false; // user failed more than allowed fails
        }

        $isFailedInCourse = $userFailedCourses->where('course_id',$course_id);
        if (! $isFailedInCourse->count() ){
            return false;
        }

        return true;

  }

  public function courseXtraTray($course_id)
  {
      if ($course_id == 155){
          return true;
      }
  }

  public function giveUserExtraTray($user, $course_id, $site_id)
  {
      if ($this->userGetXtraTray($user, $course_id)){
        return true;
      }
      if ($this->userGetFailedXtraTray($user, $site_id, $course_id)){
        return true;
      }
      // if course دورة تاريخ اليمن في الحاضر والماضي give evry user extra tray
      if ($this->courseXtraTray($course_id)){
        return true;
      }

      return false;
  }


}
