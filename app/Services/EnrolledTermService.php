<?php

namespace App\Services;
use App\Models\EnrolledTerm;
use App\Models\Lookup;

class EnrolledTermService
{

  public function getStudentEnrolledTermById($student, $enrolledId)
  {
      return EnrolledTerm::withDetails()->where('student_id', $student->id)->find($enrolledId);
  }

  public function getWithDetailsById($id)
  {
      return EnrolledTerm::withDetails()->find($id);
  }

  public function getWithFullDetailsById($id)
  {
      return EnrolledTerm::withDetails()
        ->with(['enrolled_term_courses.course','pay_fees'])
        ->find($id);
  }

  public function getStudentUnfinishedEnrollTerm($student)
  {
      return EnrolledTerm::where('student_id', $student->id)->unFinished()->first();
  }

  public function getStudentStudyingTerm($student)
  {
      return EnrolledTerm::where('student_id', $student->id)->studying()->first();
  }

  // هل الطالب نجح فى التيرمات التى حجزها - قد يكون نجح ولكن باقى له دورات
  public function isStudentSuccessedInEnrolledTerms($student, $enrolled)
  {
      return EnrolledTerm::where('student_id', $student->id)
        ->where('enrolled_id', $enrolled->id)
        ->where('success_status_id', '!=', Lookup::getSuccessSuccessStatus())
        ->exists() ? false : true;
  }

  public function isStudentFailedInAnyEnrolledTerm($student, $enrolled)
  {
      return EnrolledTerm::where('student_id', $student->id)
        ->where('enrolled_id', $enrolled->id)
        ->where('success_status_id', Lookup::getFailedSuccessStatus())
        ->exists();
  }

  // هل الطالب انهى التيرمات التى حجزها - قد يكون انهاها ولكن باقى له دورات تحتاجلتيرمات اخرى
  public function isStudentFinishedEnrolledTerms($student, $enrolled)
  {
      return EnrolledTerm::where('student_id', $student->id)
        ->where('enrolled_id', $enrolled->id)
        ->where('study_status_id', '!=', Lookup::getFinishedEnrollTermStudyStatus())
        ->exists() ? false : true;
  }

  // هل الطالب ما زال يدرس اى من تيرمات الالتحاق
  public function isStudentStudyingAnyEnrolledTerms($student, $enrolled)
  {
      return EnrolledTerm::where('student_id', $student->id)
        ->where('enrolled_id', $enrolled->id)
        ->where('study_status_id', Lookup::getStudyingEnrollTermStudyStatus())
        ->exists();
  }

  public function getStudentNextTerm($student, $enrolled)
  {

      $termsIds = EnrolledTerm::where('enrolled_id', $enrolled->id)->where('student_id', $student->id)->pluck('term_id');
      $currentTerm = \App\Models\Term::whereIn('id', $termsIds)->orderBy('sort', 'DESC')->first();
      if (! $currentTerm){
          return null;
      }

      return \App\Models\Term::where('sort', $currentTerm->sort + 1)->active()->first();

  }

  public function storeEnrolledTerm($data)
  {
      return EnrolledTerm::create($data);
  }

  public function updateEnrolledTerm($enrolledTerm, $data)
  {
      return tap($enrolledTerm)->update($data);
  }





}
