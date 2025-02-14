<?php

namespace App\Services;
use App\course_test_result;
use App\helpers\domainHelper;
use App\Models\CourseTrack;
use App\Models\StudentSeen;
use App\Models\TestResult;
use App\TermTestResult;
use Illuminate\Support\Facades\Auth;

class TestResultsService
{

  // عدد اختبارات الطالب فى اختبار معين
  public function getStudentTestTestsCount($student, $testId)
  {
      return TestResult::where('student_id', $student->id)->where('test_id', $testId)->count();
  }

  public function store($data = [])
  {
      return TestResult::create([
        'student_id' => $data['student_id'],
        'course_id' => $data['course_id'],
        'test_id' => $data['test_id'],
        'degree' =>  $data['degree'],
        'rate' => $data['rate'],
        'percentage' => $data['percentage'],
        'locale' => app()->getLocale(),
      ]);
  }



    public function CheckCourseFinished($course)
  {
      // Get course_test_result when all tests are seen
      $courseTracks = CourseTrack::query()->where('course_id',$course->id)->orderBy('sort')->get();
      $countSimilar = 0;
      // Check if every lesson/test in course_track is in student_seen
      foreach ($courseTracks as $courseTrack) {
          $studentSeen = StudentSeen::where('student_id', Auth::id())
              ->where('seenable_id', $courseTrack->courseable_id)
              ->where('seenable_type', $courseTrack->courseable_type)
              ->first();

          // when find it increase it in array
          if ($studentSeen) {
              $countSimilar++;
          }
      }

      if ($countSimilar == $courseTracks->count())  // This means the course is finished
      {
          $courseResults = TestResult::query()->where('student_id',Auth::id())
              ->where('course_id',$course->id)->get();


          // To get the largest value of tests with the same id  --- لو الطالب امتحن نفس الامتحان اكثر من مرة بناخد اعلى قيمة
          $resultsGroupedByTestId = $courseResults->groupBy('test_id');

          // قسم العناصر إلى مجموعات واختر أعلى عنصر في كل مجموعة
          $processedResults = $resultsGroupedByTestId->map(function ($group) {
              // احصل على العنصر ذو أعلى قيمة degree في المجموعة
              $highestDegreeItem = $group->sortByDesc('degree')->first();
              return $highestDegreeItem;
          })->values();


          // To check if there is one test at least rate = 0 (fail)
          $foundRateZero = false;
          foreach ($processedResults as $item) {
              if ($item->rate == 0) {
                  $foundRateZero = true;
                  break; // Stop looping if found
              }
          }

          if ($foundRateZero == true){ // set rate = 0 && degree = 0
              $courseDegree =0;
              $courseRate =0;
          } else{

//              // To get the largest value of tests with the same id  --- لو الطالب امتحن نفس الامتحان اكثر من مرة بناخد اعلى قيمة
//              $resultsGroupedByTestId = $courseResults->groupBy('test_id');
//
//              // قسم العناصر إلى مجموعات واختر أعلى عنصر في كل مجموعة
//              $processedResults = $resultsGroupedByTestId->map(function ($group) {
//                  // احصل على العنصر ذو أعلى قيمة degree في المجموعة
//                  $highestDegreeItem = $group->sortByDesc('degree')->first();
//                  return $highestDegreeItem;
//              })->values();

              $sumDegreeOfTests = $courseResults->sum('degree');
              $courseDegree = ($sumDegreeOfTests / $courseResults->count());
              $courseDegree = domainHelper::formatDegreeNumber($courseDegree); // number_format($studentDegree, 2);
              $courseRate = domainHelper::calculateTestRate($courseDegree);
          }

          $term_id = $course->terms->first()->id;

          course_test_result::updateOrCreate(
              [
                  'course_id'=>$course->id,
                  'user_id'=>Auth::id(),
              ],
              [
                  'user_id'=>Auth::id(),
                  'site_id'=>$course->site_id,
                  'term_id'=>$term_id,
                  'course_id'=>$course->id,
                  'degree'=>$courseDegree,
                  'rate'=>$courseRate,
                  'locale'=>app()->getLocale(),
              ]
          );

      }
  } //end of function

    public function CheckTermFinished($course)
    {
        $term = $course->terms->first();
        $termCourses = $term->courses;
        $countSimilar = 0;
        // Check if every course in course_site is in course_test_result
        foreach ($termCourses as $termCourse) {
            $courseTestResult = course_test_result::where('user_id', Auth::id())
                ->where('term_id', $term->id)->where('course_id', $termCourse->id)->first();

            // when find it increase it in array
            if ($courseTestResult) {
                $countSimilar++;
            }
        }

        if ($countSimilar == $termCourses->count())  // This means the term is finished because number of termCourses == number of courses that has result
        {
            $termCourseTestResult = course_test_result::where('user_id', Auth::id())
                ->where('term_id', $term->id)->get();
            // To check if there is one course at least rate = 0 (fail)
            $foundRateZero = false;
            foreach ($termCourseTestResult as $item) {
                if ($item->rate == 0) {
                    $foundRateZero = true;
                    break; // Stop looping if found
                }
            }

            if ($foundRateZero == true){ // set rate = 0 && degree = 0
                $termDegree =0;
                $termRate =0;
            } else{
                $sumDegreeOfCourses = $termCourseTestResult->sum('degree');
                $termDegree = ($sumDegreeOfCourses / $termCourseTestResult->count());
                $termDegree = domainHelper::formatDegreeNumber($termDegree); // number_format($studentDegree, 2);
                $termRate = domainHelper::calculateTestRate($termDegree);
            }

            TermTestResult::updateOrCreate(
                [
                    'term_id'=>$term->id,
                    'user_id'=>Auth::id(),
                ],
                [
                    'user_id'=>Auth::id(),
                    'site_id'=>$term->site_id,
                    'term_id'=>$term->id,
                    'degree'=>$termDegree,
                    'rate'=>$termRate,
                    'locale'=>app()->getLocale(),
                ]
            );

        }
    }


} //end of class
