<?php

namespace App\Services;

class EjazaService
{

  private $user;
  private $siteId;
  private $courseId;
  private $testResult;
  public $visualTestResult;

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function setSiteId($siteId)
  {
      $this->siteId = $siteId;
      return $this;
  }

  public function setCourseId($courseId)
  {
      $this->courseId = $courseId;
      return $this;
  }

  public function setTestResult($testResult)
  {
      $this->testResult = $testResult;
      return $this;
  }

  public function getEjazaVisualTestResult()
  {
      return \App\CourseTestVisual::where('site_id', $this->siteId)
        ->where('course_id', $this->courseId)
        ->where('user_id', $this->user->id)
        ->first();
  }

  public function setEjazaVisualTestResult()
  {
      if(! $this->visualTestResult){
        $this->visualTestResult = $this->getEjazaVisualTestResult();
      }
  }




  public function sucessInEjazaTest()
  {
      return $this->testResult->degree >= ejazaPointsOfSuccess();
  }

  public function hasEjazaVisualTest()
  {
      $this->setEjazaVisualTestResult();
      return $this->visualTestResult; // user uploaded video or not
  }

  public function sucessInEjazaVisualTest()
  {
      $this->setEjazaVisualTestResult();

      if(! $this->hasEjazaVisualTest()){
        return false;
      }

      return $this->visualTestResult->rate == 1;
  }

  public function faildInEjazaVisualTest()
  {
      $this->setEjazaVisualTestResult();

      if(! $this->hasEjazaVisualTest()){
        return false;
      }

      return $this->visualTestResult->rate == 2;
  }

  public function userSucessInEjaza()
  {
      if (! $this->sucessInEjazaTest()){
          return false;
      }

      if(! $this->hasEjazaVisualTest()){
          return false;
      }

      if(! $this->sucessInEjazaVisualTest()){
          return false;
      }

      return true;
  }


}
