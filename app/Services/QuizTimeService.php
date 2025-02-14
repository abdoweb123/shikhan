<?php

namespace App\Services;

use DB;
use App\MemberQuizTime;
use Carbon\Carbon;

class QuizTimeService
{

  private $userId;
  private $siteId;
  private $courseId;
  private $termId;
  private $questionPeriod;
  private $questionsCount;
  private $userQuizTime;

  public function __construct( $params = [] )
  {

    $this->userId = $params['user_id'] ?? null;
    $this->siteId = $params['site_id'] ?? null;
    $this->courseId = isset($params['course_id']) ? $params['course_id'] : null;
    $this->termId = isset($params['term_id']) ? $params['term_id'] : null;
    $this->questionPeriod = $params['question_period'] ?? null;
    $this->questionsCount = $params['questions_count'] ?? null;

  }

  public function getQuestionPeriod()
  {
      return $this->questionPeriod ?? config('project.default_question_period');
  }

  public function getQuestionPeriodUnit()
  {
      return config('project.default_question_period_unit');
  }

  public function getQuestionPeriodUnitTitle()
  {
      $unit = $this->getQuestionPeriodUnit();
      if($unit == 'm'){
        return 'دقيقة';
      }
  }

  public function getQuizFullTime()
  {
    // if(auth()->id() == 5972){
    //   return 3 * $this->questionsCount;
    // }

    return $this->getQuestionPeriod() * $this->questionsCount;

  }

  public function getUserQuizTime()
  {

      $this->userQuizTime = MemberQuizTime::firstOrCreate(
        [ 'user_id' => $this->userId, 'site_id' => $this->siteId, 'course_id' => $this->courseId, 'term_id' => $this->termId ],
        [ 'questions_count' => $this->questionsCount, 'question_period' => $this->getQuestionPeriod(), 'start_time' => now(), 'elapsed_time' => '0' ]
      );
      return $this->userQuizTime;
  }

  public function quizFullTimeChanged()
  {
      if ( $this->userQuizTime->questions_count == $this->questionsCount && $this->userQuizTime->question_period == $this->getQuestionPeriod() ){
        return false;
      }
      return true;
  }

  public function updateQuizFullTime()
  {
        // $userQuizTime = MemberQuizTime::where([
        //   'user_id' => $this->userId,
        //   'site_id' => $this->siteId,
        //   'course_id' => $this->courseId
        // ])->first();
        $userQuizTime = MemberQuizTime::where(function($q){
          $q->where('user_id',$this->userId);
          $q->where('site_id',$this->siteId);
          if ($this->courseId){
            $q->where('course_id',$this->courseId);
          }
          if ($this->termId){
            $q->where('term_id',$this->termId);
          }
        })->first();


        $userQuizTime->questions_count = $this->questionsCount;
        $userQuizTime->question_period = $this->getQuestionPeriod();
        $userQuizTime->save();

        $this->userQuizTime = $userQuizTime;
        return $userQuizTime;
  }

  public function deleteQuizTime()
  {
        // $userQuizTime = MemberQuizTime::where([
        //   'user_id' => $this->userId,
        //   'site_id' => $this->siteId,
        //   'course_id' => $this->courseId
        // ])->delete();

        $userQuizTime = MemberQuizTime::where(function($q){
          $q->where('user_id',$this->userId);
          $q->where('site_id',$this->siteId);
          if ($this->courseId){
            $q->where('course_id',$this->courseId);
          }
          if ($this->termId){
            $q->where('term_id',$this->termId);
          }
        })->delete();

  }

  public function getQuizElapsedTime()
  {
      return Carbon::now()->diffInSeconds( $this->userQuizTime->start_time );
  }

  public function getQuizRemainTime()
  {
      return ($this->getQuizFullTime() * 60) - $this->getQuizElapsedTime();
  }

  public function startQuiz()
  {
      $this->getUserQuizTime();

      if( $this->quizFullTimeChanged() ){
          $this->updateQuizFullTime();
      }

      return[
        'quizFullTime' => $this->getQuizFullTime(),
        'questionPeriodUnitTitle' => $this->getQuestionPeriodUnitTitle(),

        'quizStartTime' => $this->userQuizTime->start_time,
        'quizElapsedTime' => $this->getQuizElapsedTime(),
        'quizRemainTime' => $this->getQuizRemainTime(),
        'userHasRemainTime' =>  $this->getQuizRemainTime() > 0,
      ];

  }





}
