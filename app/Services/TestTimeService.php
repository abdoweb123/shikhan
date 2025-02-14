<?php

namespace App\Services;
use App\Models\TestTime;
use DB;
use Carbon\Carbon;

class TestTimeService
{

  private $studentId;
  private $enrolledId;
  private $testId;
  private $testDuration;
  private $studentTestTime;

  public function __construct( $params = [] )
  {

    $this->studentId = $params['student_id'] ?? null;
//    $this->enrolledId = $params['enrolled_id'] ?? null;
    $this->testId = $params['test_id'] ?? null;
    $this->testDuration = $params['test_duration'] ?? null;

  }

  public function getTestDurationUnit()
  {
      return config('domain.default_test_duration_unit');
  }

  public function getTestDurationUnitTitle()
  {
      $unit = $this->getTestDurationUnit();

      if($unit == 'm'){
        return 'دقيقة';
      }
  }

  public function getOrCreateStudentTestTime()
  {
      $this->studentTestTime = TestTime::firstOrCreate(
        [ 'student_id' => $this->studentId, 'test_id' => $this->testId ],
        [ 'test_duration' => $this->testDuration, 'start_time' => now(), 'elapsed_time' => '0' ]
      );

      return $this->studentTestTime;
  }

  public function testDurationChanged()
  {
      if ( $this->studentTestTime->test_duration == $this->testDuration ){
        return false;
      }

      return true;
  }

  public function updateTestFullTime()
  {
        $studentTestTime = TestTime::where([
          'student_id' => $this->studentId,
          'test_id' => $this->testId
        ])->first();

        $studentTestTime->test_duration = $this->testDuration;
        $studentTestTime->save();

        $this->studentTestTime = $studentTestTime;

        return $studentTestTime;
  }

  public function deleteTestTime()
  {
        $studentTestTime = TestTime::where([
          'student_id' => $this->studentId,
          'test_id' => $this->testId
        ])->delete();

  }

  public function getTestElapsedTime()
  {
      return Carbon::now()->diffInSeconds( $this->studentTestTime->start_time );
  }

  public function getTestRemainTime()
  {
      return ($this->testDuration * 60) - $this->getTestElapsedTime();
  }

  public function startTest()
  {
      $this->getOrCreateStudentTestTime();

      if( $this->testDurationChanged() ){
          $this->updateTestFullTime();
      }

      return [
        'testDuration' => $this->testDuration,
        'testDurationUnitTitle' => $this->getTestDurationUnitTitle(),

        'testStartTime' => $this->studentTestTime->start_time,
        'testElapsedTime' => $this->getTestElapsedTime(),
        'testRemainTime' => $this->getTestRemainTime(),
        'userHasRemainTime' =>  $this->getTestRemainTime() > 0,
      ];

  }



}
