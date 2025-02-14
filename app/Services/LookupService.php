<?php

namespace App\Services;
use App\Models\Lookup;

class LookupService
{

  public function getActiveStatuses()
  {
    return Lookup::where('type', 'status')->active()->get();
  }

  public function getActiveGenders()
  {
    return Lookup::where('type', 'gender')->active()->get();
  }


  // Domain
  public function getActiveGetQuestionsStatuses()
  {
    return Lookup::where('type', 'get_questions_status')->active()->get();
  }

  public function getActiveEnrollApprovedStatuses()
  {
    return Lookup::where('type', 'enroll_approved_status')->active()->get();
  }

  public function getActiveEnrollTermApprovedStatuses()
  {
    return Lookup::where('type', 'enroll_term_approved_status')->active()->get();
  }

  public function getActiveEnrollStudyStatuses()
  {
    return Lookup::where('type', 'enroll_study_status')->active()->get();
  }

  public function getActiveEnrollTermStudyStatuses()
  {
    return Lookup::where('type', 'enroll_term_study_status')->active()->get();
  }

  public function getActiveEnrollPayStatuses()
  {
    return Lookup::where('type', 'enroll_pay_status')->active()->get();
  }

  public function getActiveEnrollTermPayStatuses()
  {
    return Lookup::where('type', 'enroll_term_pay_status')->active()->get();
  }

  public function getActiveEnrollTermCoursePayStatuses()
  {
    return Lookup::where('type', 'enroll_term_course_pay_status')->active()->get();
  }

  public function getActiveEnrollTermCourseRealStudyStatuses()
  {
    return Lookup::where('type', 'real_study')->active()->get();
  }


  // fee types
  // مصاريف دراسة ومصاريف استخراج شهادة  لكل قسم شهادة
  public function getActiveEnrollFeeTypes()
  {
    return Lookup::where('type', 'enroll_fee_type')->active()->get();
  }
  // مصاريف ساعات دراسية
  public function getActiveEnrollTermFeeTypes()
  {
    return Lookup::where('type', 'enroll_term_fee_type')->active()->get();
  }
  public function getActiveEquivalentFeeTypes()
  {
    return Lookup::where('type', 'equivalent_fee_type')->active()->get();
  }



  public function getActivePayStatuses()
  {
    return Lookup::where('type', 'pay_status')->active()->get();
  }

  public function getActiveTestTypes()
  {
    return Lookup::where('type', 'test_type')->active()->get();
  }
  public function getActiveLessonStudyTypes()
  {
    return Lookup::where('type', 'lesson_study_type')->active()->get();
  }


  public function getActiveCourseActiveStatuses()
  {
    return Lookup::where('type', 'course_active_status')->active()->get();
  }



}
