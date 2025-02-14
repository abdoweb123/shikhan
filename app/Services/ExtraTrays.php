<?php

namespace App\Services;

use DB;

class ExtraTrays
{

  private $user;
  private $site_id;
  private $course_id;
  private $term_id;
  private $locale;
  private $globalService;

  public function __construct( $params = [] )
  {
      $this->user = isset($params['user']) ? $params['user'] : null;
      $this->site_id = isset($params['site_id']) ? $params['site_id'] : null;
      $this->course_id = isset($params['course_id']) ? $params['course_id'] : null;
      $this->term_id = isset($params['term_id']) ? $params['term_id'] : null;
      $this->locale = isset($params['locale']) ? $params['locale'] : null;

      $this->globalService = new \App\Services\GlobalService;
  }

  // لفتح ا اختبارات لهم بعد ما استنفذو مرات الاختبار members_extra_trays طلاب يتم اضافتهم الى جدول
  public function getUserEmailXtraTrays()
  {
      return DB::table('members_extra_trays')
        ->where('user_id', $this->user->id)
        ->when($this->course_id, function($q){
          return $q->where('course_id', $this->course_id);
        })
        ->when($this->term_id, function($q){
          return $q->where('term_id', $this->term_id);
        })
        ->when($this->locale, function($q){
            return $q->where('locale', $this->locale);
        })->first();
  }

  public function getUserExtraTrays($params=[])
  {
      return $this->user->extra_trays()
          ->when($this->locale, function($q){
              return $q->where('locale', $this->locale);
          })->with('course:id,title')->get();
  }

  public function getCourseXtraTrays()
  {
      return \App\course::find($this->course_id)->getMaxTrys();
      // if ($this->course_id == 155){
      //     return 5;
      // }
      // return false;
  }

  public function getEjazaXtraTrays()
  {
      if( array_search($this->course_id, ejazaExtraTraysIds()) === null ){
        return 0;
      }

      $courseTestResult = DB::table('course_tests_results')
        ->where('user_id', $this->user->id)
        ->where('course_id', $this->course_id)
        ->select(DB::raw('MAX(degree) as max_degree'))
        ->groupBy('course_id')
        ->first();

      if(! $courseTestResult){
        return 0;
      }

      if( $courseTestResult->max_degree >= $this->globalService->pointOfSuccessEjaza_289){
        return 0;
      }

      $userSuccessedVisualTest = \App\CourseTestVisual::where('course_id', $this->course_id)->where('user_id', $this->user->id)->success()->exists();
      if(! $userSuccessedVisualTest ){
        return 0;
      }

      return 3;

  }

  // 01
  public function getUserTestsResults ()
  {
      return $this->globalService->getUserCoursesMaxDegrees($this->user, [ 'siteId' => $this->site_id ]);
  }

  // 02
  public function getUserFaildCourses()
  {
      $userFailedCourses = $this->globalService->userFailedCourses($this->getUserTestsResults());
      return $userFailedCourses;
  }

  // 03
  public function getSiteValidCourses()
  {
      $sitCoursesCount = $this->globalService->getValidCourses([
        'siteId' => $this->site_id,
        'count' => true
      ]);

      return $sitCoursesCount;
  }

  // 04
  public function isUserFinishedSite()
  {
      $userFinishedSite = $this->getSiteValidCourses() == $this->getUserTestsResults()->count();
      if (! $userFinishedSite) {
        return false;
      }
      return true;
  }

  // 05
  public function isUserFaildInCurrentCourse()
  {
      $isFailedInCourse = $this->getUserFaildCourses()->where('course_id',$this->course_id);
      if (! $isFailedInCourse->count() ){
          return false;
      }
      return true;
  }

  // group
  public function getFailedXtraTrays()
  {

      if (! $this->isUserFinishedSite()){
          return false;
      }

      if (! $this->isUserFaildInCurrentCourse()){
          return false;
      }

      // لو اختبر ابدبلوم بالكامل ورسب فى مادتين يتم فتح 8 محاولات للدورتين التى رسب فيهم
      // if ( $this->getUserFaildCourses()->count() == 2) {
      //     return 8;
      // }

      // لو اختبر الدبلوم بالكامل ورسب فى مادة واحدة يتم فتح عدد لانهائى من المحاولات فى المادة التى رسب فيها
      // if ( $this->getUserFaildCourses()->count() == 1) {
      //     return 200;
      // }

      // لو اختبر الدبلوم بالكامل يتم فتح عدد محاولات لا نهائى للدورات التى رسب فيها
      return 200;

  }

  public function getUserXtraTrays()
  {
      $trays = 0;
      $userExtraTrays = [];


      $userEmailXtraTrays = $this->getUserEmailXtraTrays();
      if ( $userEmailXtraTrays ){
        $trays = $userEmailXtraTrays->trays;
      }

      $courseXtraTrays = $this->getCourseXtraTrays();
      if ( $courseXtraTrays ){
        $trays = ($trays > $courseXtraTrays) ? $trays : $courseXtraTrays; // take the gigest of them
      }

      $userFailedXtraTrays = $this->getFailedXtraTrays();
      if ( $userFailedXtraTrays ){
        $trays = ($trays > $userFailedXtraTrays) ? $trays : $userFailedXtraTrays; // take the gigest of them
      }

      $ejazaXtraTrays = $this->getEjazaXtraTrays();
      if ( $ejazaXtraTrays ){
        $trays = ($trays > $ejazaXtraTrays) ? $trays : $ejazaXtraTrays;
      }

      if( $userEmailXtraTrays || $courseXtraTrays || $userFailedXtraTrays || $ejazaXtraTrays) {
        $userExtraTrays['userHasExtraTrays'] = true;
      } else {
        $userExtraTrays['userHasExtraTrays'] = false;
      }

      $userExtraTrays['extraTrays'] = $trays;

      return $userExtraTrays;

  }

}
