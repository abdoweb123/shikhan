<?php

namespace App\Services;
use App\Models\Enrolled;

class StudyService
{

  // 1- المواد الدراسية لالتحاق معين
  public function getStudentEnrolldStudyingCoursesById($student, $enrolledId)
  {
      return Enrolled::withDetails()
        ->with(['enrolled_terms' => function($q) {
          $q->afterEnrolled()->with(['term','enrolled_term_courses' => function($q) {
            $q->afterEnrolled()
              ->with(['course' => function($q) {
                  $q->active()->withLessonsCount()->withTestsCount();
                }]);

          }]);
        }])->where('student_id', $student->id)->find($enrolledId);
  }

  // 2- مادة معينة بالتراك
  public function getStudentEnrolldStudyingCourseByIdAndCourseId($student, $enrolledId, $courseId)
  {
      return Enrolled::withDetails()
        ->with(['enrolled_terms' => function($q) use($courseId, $student){
          $q->afterEnrolled()->whereRelation('enrolled_term_courses','course_id', $courseId)
          ->with(['term','enrolled_term_courses' => function($q) use($courseId, $student){
              $q->where('course_id', $courseId)->afterEnrolled()
                ->with([
                    'course' => function($q) { $q->active(); },
                    'course.course_track.courseable.seen' => function($q) use($student) { $q->where('student_id', $student->id); },
                    'course.course_track' => function($q) { $q->orderBy('sort'); }
                ]);
            }]);
        }])
        ->with(['enrolled_research' => function($q) {
          $q->with('teacher');
        }])
        ->where('student_id', $student->id)
        ->find($enrolledId);
  }

  // مادة معينة بالتراك - فى حالة التحاق بحثى
  public function getEnrolldResearchCourseByIdAndCourseId($student, $enrolledId, $courseId, $lessonOrTest = null)
  {
      return Enrolled::with(['enrolled_term_courses' => function($q) use($courseId, $lessonOrTest) {
          $q->where('course_id', $courseId)->afterEnrolled()
            ->with([
              'course' => function($q) { $q->active(); },
              'course.course_track' => function($q) use($lessonOrTest){
                  if ($lessonOrTest == 'Lesson'){
                      $q->Lesson()->orderBy('sort');
                  } elseif ($lessonOrTest == 'Test'){
                      $q->Test()->orderBy('sort')->with('courseable.test_results');
                  } else {
                      $q->orderBy('sort');
                  }
              }
            ]);
        }])
        ->where('student_id', $student->id)
        ->find($enrolledId);

  }


  // 3- درس
  public function getStudentEnrolldStudyingCourseLessonByIds($student, $enrolledId, $courseId, $lessonOrTestId)
  {
    return Enrolled::with([
      'enrolled_terms' => function($q) use($courseId, $lessonOrTestId){
          $q->afterEnrolled()->whereRelation('enrolled_term_courses','course_id', $courseId)
            ->with([
              'enrolled_term_courses' => function($q) use($courseId, $lessonOrTestId){
                $q->where('course_id', $courseId)
                  ->afterEnrolled()
                  ->with([
                    'course' => function($q) { $q->active(); },
                    'course.course_track.courseable' => function($q) { $q->withActiveFullDetails(); },
                    'course.course_track' => function($q) use($lessonOrTestId) { $q->where('courseable_id', $lessonOrTestId); }
                  ]);
            }]);
      }])->where('student_id', $student->id)->find($enrolledId);
  }

  // 4- اختبار
  public function getStudentEnrolldTestByIds($student, $enrolledId, $courseId, $lessonOrTestId)
  {
    return Enrolled::with([
      'enrolled_terms' => function($q) use($courseId, $lessonOrTestId){
          $q->afterEnrolled()->whereRelation('enrolled_term_courses','course_id', $courseId)
            ->with([
              'enrolled_term_courses' => function($q) use($courseId, $lessonOrTestId){
                $q->where('course_id', $courseId)
                  ->afterEnrolled()
                  ->with([
                    'course' => function($q) { $q->active(); },
                    'course.course_track.courseable' => function($q) { $q->withActiveFullDetails(); },
                    'course.course_track' => function($q) use($lessonOrTestId) { $q->where('courseable_id', $lessonOrTestId); }
                  ]);
            }]);
      }])->where('student_id', $student->id)->find($enrolledId);
  }

  public function isStudentSeePreviousLesson($student, $course, $courseTrack)
  {

      if ($courseTrack->sort == 1){
          return true;
      } else {
          $previousLesson = $course->course_track()->where('sort', '<', $courseTrack->sort)->orderBy('sort', 'Desc')->first();

          if (!$previousLesson->courseable){ // test or lesson deleted
              return true;
          }
          if ($previousLesson->isLesson()){
              return $student->seens()->lesson()->where('seenable_id', $previousLesson->courseable_id)->exists();
          }
          if ($previousLesson->isTest()){
              return $student->seens()->test()->where('seenable_id', $previousLesson->courseable_id)->exists();
          }
      }

  }


}
