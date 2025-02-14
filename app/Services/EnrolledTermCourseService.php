<?php

namespace App\Services;
use App\Models\EnrolledTermCourse;
use App\Models\Lookup;

class EnrolledTermCourseService
{

  public function getById($Id)
  {
    return EnrolledTermCourse::find($Id);
  }

  public function isStudentSuccessedInCourse($studentId, $courseId)
  {
      return EnrolledTermCourse::where('stdudent_id', $studentId)->where('course_id', $courseId)->successed()->exists();
  }

  // هل الطالب نجح فى دورات التيرم
  public function isStudentSuccessedInTermCourses($student, $enrolledTerm)
  {
      return EnrolledTermCourse::where('student_id', $student->id)
        ->where('enrolled_term_id', $enrolledTerm->id)
        ->where('success_status_id', '!=', Lookup::getSuccessSuccessStatus())
        ->exists() ? false : true;
  }

  // هل الطالب رسب فى اى دورة فى التيرم
  public function isStudentFailedInAnyTermCourses($student, $enrolledTerm)
  {
      return EnrolledTermCourse::where('student_id', $student->id)
        ->where('enrolled_term_id', $enrolledTerm->id)
        ->where('success_status_id', Lookup::getFailedSuccessStatus())
        ->exists();
  }

  // هل الطالب انهى دراسة كل دورات التيرم - الطالب انهى التيرم
  public function isStudentFinishedTermCourses($student, $enrolledTerm)
  {
      return EnrolledTermCourse::where('student_id', $student->id)
        ->where('enrolled_term_id', $enrolledTerm->id)
        ->where('study_status_id', '!=', Lookup::getFinishedEnrollTermCourseStudyStatus())
        ->exists() ? false : true;
  }

  // هل الطالب ما زال يدرس اى دورة من دورات التيرم
  public function isStudentStudyingAnyTermCourses($student, $enrolledTerm)
  {
      return EnrolledTermCourse::where('student_id', $student->id)
        ->where('enrolled_term_id', $enrolledTerm->id)
        ->where('study_status_id', Lookup::getStudyingEnrollTermCourseStudyStatus())
        ->exists();
  }

  // عدد الدورات المنتهية للطالب فى هذا الالتحاق
  public function getCountStudentFinishedCoursesByEnrolled($enrolled)
  {

      return EnrolledTermCourse::where('study_status_id', Lookup::getFinishedEnrollTermCourseStudyStatus())
        ->whereHas('enrolled_term', function($q) use($enrolled){
          $q->whereHas('enrolled', function($q) use($enrolled){
            $q->where('enrolled_id', $enrolled->id);
          });
        })->count();

  }

  // عدد الدورات الناجحة للطالب فى هذا الالتحاق
  public function getCountStudentSuccessedCoursesByEnrolled($enrolled)
  {

      return EnrolledTermCourse::where('success_status_id', Lookup::getSuccessSuccessStatus())
        ->whereHas('enrolled_term', function($q) use($enrolled){
          $q->whereHas('enrolled', function($q) use($enrolled){
            $q->where('enrolled_id', $enrolled->id);
          });
        })->count();

  }

  // هل الطالب مسجل فى اى دورة من هذه الدورات
  public function isStudentHasEnrolledTermCoursesByCoursesIds($student, $coursesIds)
  {
      return EnrolledTermCourse::where('student_id', $student->id)
        ->whereIn('course_id', $coursesIds)
        ->exists();
  }

  // دورات الطالب عامة بدون ربطها مع تيرم او التحاق
  public function getStudentEnrolledTermCoursesByCoursesIds($student, $coursesIds)
  {
      return EnrolledTermCourse::where('student_id', $student->id)->whereIn('course_id', $coursesIds)->with('course')->get();
  }

  public function storeEnrolledTermCourse($data)
  {
      return EnrolledTermCourse::create($data);
  }

  public function updateEnrolledTermCourse($enrolledTermCourse, $data)
  {
      return tap($enrolledTermCourse)->update($data);
  }




}
