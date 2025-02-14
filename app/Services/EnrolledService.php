<?php

namespace App\Services;
use App\Models\Enrolled;
use App\Models\EnrolledTermCourse;
use App\Models\Lookup;
use App\helpers\helper;

class EnrolledService
{

  public function getStudentEnrolleds($student)
  {
      return Enrolled::withDetails()
        ->with(['enrolled_terms' => function($q) {
            $q->with(['term']);
        }])->where('student_id', $student->id)->get();
  }

  public function getStudentEnrolledById($student, $enrolledId)
  {
      return Enrolled::withDetails()
        ->with(['enrolled_terms' => function($q) {
          $q->with(['enrolled_term_courses']);
        }])->where('student_id', $student->id)->find($enrolledId);
  }

  public function getStudentEnrolledDetailsById($student, $enrolledId)
  {
      return Enrolled::withDetails()->where('student_id', $student->id)->find($enrolledId);
  }

  public function getWithDetailsById($id)
  {
      return Enrolled::withDetails()->find($id);
  }

  public function getWithFullDetailsById($id)
  {
      return Enrolled::withDetails()
        ->with(['student' => function($q) { $q->withDetails(); }])
        ->with(['section_certificate','pay_statuses','pay_fees.pay_type', 'pay_fees.currency', 'pay_fees_study_and_term_fee_status'])
        ->find($id);
  }






  // هل الطالب انهى كل دورات القسم الشهادة
  public function isStudentFinishedAllEnrolledCourses($enrolled, $sectionCertificate)
  {
      // عدد الدورات المنتهية للطالب فى هذا الالتحاق
      $enrolledTermCourseService = new \App\Services\EnrolledTermCourseService();
      $countStudentFinishedCourses = $enrolledTermCourseService->getCountStudentFinishedCoursesByEnrolled($enrolled);

      // عدد الدورات الفعالة فى هذ القسم الشهادة
      $sectionCertificateService = new \App\Services\SectionCertificateService();
      $countSectionCertificateActiveCourses = $sectionCertificateService->getCountSectionCertificateValidCourses($sectionCertificate);

      return $countSectionCertificateActiveCourses == $countStudentFinishedCourses;
  }

  // هل الطالب نجح فى كل دورات القسم الشهادة
  public function isStudentSuccessedEnrolledCourses($enrolled, $sectionCertificate)
  {
      // عدد الدورات الناجحة للطالب فى هذا الالتحاق
      $enrolledTermCourseService = new \App\Services\EnrolledTermCourseService();
      $countStudentSuccessCourses = $enrolledTermCourseService->getCountStudentSuccessedCoursesByEnrolled($enrolled);

      // عدد الدورات الفعالة فى هذ القسم الشهادة
      $sectionCertificateService = new \App\Services\SectionCertificateService();
      $countSectionCertificateActiveCourses = $sectionCertificateService->getCountSectionCertificateValidCourses($sectionCertificate);

      return $countSectionCertificateActiveCourses == $countStudentSuccessCourses;
  }




  // اختيارات التحاق الطالب
  public function getEnrolledHeader($student, $enrolled = null)
  {

      $faculties = activeExactFaculties();

      $selectedFaculty = null;
      if ($enrolled){ $selectedFaculty = $faculties->where('id', $enrolled->faculty_id)->first(); }
      if (! $selectedFaculty){ $selectedFaculty = $faculties->first(); }

      $selectedFaculty->load('sections');
      $sections = $selectedFaculty->sections;

      $selectedSection = null;
      if ($enrolled){ $selectedSection = $sections->where('id', $enrolled->section_id)->first(); }
      if (! $selectedSection){ $selectedSection = $sections->first(); }

      $certificates = $selectedSection->certificates;
      $selectedCertificate = null;
      if ($enrolled){ $selectedCertificate = $certificates->where('id', $enrolled->certificate_id)->first(); }
      if (! $selectedCertificate){ $selectedCertificate = $certificates->first(); }

      return compact('faculties', 'selectedFaculty','sections', 'selectedSection', 'certificates', 'selectedCertificate');

  }
  // دورات الالتحاق
  public function getEnrolledCourses($student, $sectionCertificate = null, $enrolled = null)
  {

      $enrolled = $enrolled;

      if (! $sectionCertificate){
          $enrolledCoursesOutside = collect([]);
          $enrolledTerms = collect([]);
          $unEnrolledCourses = collect([]);
          $enrolledFreeCourses = collect([]);

          return compact('enrolledCoursesOutside', 'enrolledTerms', 'unEnrolledCourses', 'enrolledFreeCourses', 'enrolled');
      }


      // 01 دورات القسم المختار
      $sectionCourses = sectionCertificateService()->getSectionCertificateValidTermsAndCourses($sectionCertificate);
      $sectionCoursesIds = $sectionCourses->pluck('course_id')->toArray();

      // الدورات التى اشترك فيها الطالب من القسم المختار
      $sectionCoursesEnrolled = $student->enrolled_term_courses()->wherein('course_id', $sectionCoursesIds)->get();

      // مقسمة قسمين
      // 01 درسها داخل القسم الحالى
      $enrolledCourses = collect([]);
      if ($enrolled){
        $enrolledCourses = $sectionCoursesEnrolled->where('enrolled_id', $enrolled->id);
      }
      // 02 درسها فى قسم اخر
      $enrolledCoursesOutside = collect([]);
      if ($enrolled){
        $enrolledCoursesOutside = $sectionCoursesEnrolled->where('enrolled_id', '!=', $enrolled->id); // دورات مسجلة فى التحاق اخر ولكنها من ضمن دورات القسم الحالى اى مكررة مع قسمين
      } else {
        $enrolledCoursesOutside = $sectionCoursesEnrolled;
      }

      $sectionCoursesEnrolledCoursesIds = $sectionCoursesEnrolled->pluck('course_id')->toArray();

      // 03 الدورات التى لم يختاراها الطالب من دورات القسم المختار - الممكن ان يحجزها
      $unEnrolledCourses = $sectionCourses->whereNotIn('course_id', $sectionCoursesEnrolledCoursesIds);
      // الدورات المعتمدة على بعضها
      // foreach ($unEnrolledCourses as $unEnrolledCourse) {
      //   $courseDependenciesIds = $unEnrolledCourse->course?->courses_dependencies()->pluck('id');
      //   foreach ($courseDependenciesIds ?? [] as $courseDependenciesId) {
      //     if (! enrolledTermCourseService()->isStudentSuccessedInCourse($student->id, $courseDependenciesId)){
      //       unEnrolledCourses
      //     }
      //   }
      // }
      // $unEnrolledCourses = $unEnrolledCourses->filter(function($item) {
      //     return $item->id != 2;
      // });


      // 04 الدورات الحرة - الدورات المسجلة فى هذه الالتحاق وليست من ضمن القسم المختار
      $enrolledFreeCourses = collect([]);
      if($enrolled){
        $enrolledFreeCourses = $enrolled->enrolled_term_courses()->whereNotIn('course_id', $sectionCoursesIds)->with('course')->get();
      }


      $enrolledTerms = [];
      $enrolledTermsGrouped = $enrolledCourses->groupBy('enrolled_term_id');


      foreach ($enrolledTermsGrouped as $enrolledTermId => $enrolledTermCourses) {
        $enrolledTerm = \App\Models\EnrolledTerm::where('id', $enrolledTermId)->first();
        $term = \App\Models\Term::where('id', $enrolledTerm->term_id)->first();
        $term->enrolledTerm = $enrolledTerm;
        $term->enrolledTermCourses = $enrolledTermCourses;
        $enrolledTerms[] = $term;
      }
      $enrolledTerms = collect($enrolledTerms);


      $enrolledCoursesOutside = collect($enrolledCoursesOutside);

      return compact('enrolledCoursesOutside', 'enrolledTerms', 'unEnrolledCourses', 'enrolledFreeCourses', 'enrolled');

  }

  public function getEnrolledEquivalentHeader($student, $enrolled = null)
  {

      $faculties = collect([activeEquivalentDepartment()]);

      $selectedFaculty = null;
      if ($enrolled){ $selectedFaculty = $faculties->where('id', $enrolled->faculty_id)->first(); }
      if (! $selectedFaculty){ $selectedFaculty = $faculties->first(); }

      $selectedFaculty->load('sections');
      $sections = $selectedFaculty->sections;

      $selectedSection = null;
      if ($enrolled){ $selectedSection = $sections->where('id', $enrolled->section_id)->first(); }
      if (! $selectedSection){ $selectedSection = $sections->first(); }

      $certificates = $selectedSection->certificates;
      $selectedCertificate = null;
      if ($enrolled){ $selectedCertificate = $certificates->where('id', $enrolled->certificate_id)->first(); }
      if (! $selectedCertificate){ $selectedCertificate = $certificates->first(); }

      return compact('faculties', 'selectedFaculty','sections', 'selectedSection', 'certificates', 'selectedCertificate');

  }
  // دورات الالتحاق لمعادلة البلدة
  public function getEnrolledCoursesEquivalentBalda($enrolled)
  {
        $enrolled = $enrolled;

        // $enrolledCourses = $enrolled->enrolled_term_courses()->with('course')->get();
        // $enrolledTerms = [];
        // $enrolledTermsGrouped = $enrolledCourses->groupBy('enrolled_term_id');
        // foreach ($enrolledTermsGrouped as $enrolledTermId => $enrolledTermCourses) {
        //   $enrolledTerm = \App\Models\EnrolledTerm::where('id', $enrolledTermId)->first();
        //   $term = \App\Models\Term::where('id', $enrolledTerm->term_id)->first();
        //   $term->enrolledTerm = $enrolledTerm;
        //   $term->enrolledTermCourses = $enrolledTermCourses;
        //   $enrolledTerms[] = $term;
        // }
        // $enrolledTerms = collect($enrolledTerms);

        $equivalentEnrolledCourses = $enrolled->enrolled_term_courses()->with('course')->get();

        return compact('equivalentEnrolledCourses','enrolled');
  }


  public function getStudentByFacultySectionCertificate($studentId, $facultyId, $sectionId, $certificateId)
  {
      return Enrolled::where('student_id', $studentId)->where('faculty_id', $facultyId)
        ->where('section_id', $sectionId)->where('certificate_id', $certificateId)->first();
  }

  public function storeEnrolled($data)
  {
      return Enrolled::create([
        'student_id' => $data['student_id'],
        'faculty_id' => $data['faculty_id'],
        'section_certificate_id' => $data['section_certificate_id'],
        'section_id' => $data['section_id'],
        'certificate_id' => $data['certificate_id'],
        'locale' => isset($data['locale']) ? $data['locale'] : app()->getlocale(),
        'approved_status_id' => isset($data['approved_status_id']) ? $data['approved_status_id'] : Lookup::getDefaultEnrollAppreovedStatus(),
        'pay_status_id' => isset($data['pay_status_id']) ? $data['pay_status_id'] : Lookup::getDefaultEnrollPayStatus(),
        'study_status_id' => isset($data['study_status_id']) ? $data['study_status_id'] : Lookup::getDefaultEnrollStudyStatus(),
        'status_id' => isset($data['status_id']) ? $data['status_id'] : Lookup::getDefaultStatus(),
        'success_status_id' => isset($data['success_status_id']) ? $data['success_status_id'] : Lookup::getDefaultSuccessStatus(),
        'current_status_id' => isset($data['current_status_id']) ? $data['current_status_id'] : Lookup::getDefaultCurrentStatus(),
        'code' => enrolledService()->generateEnrolledCode(),
        'access_user_id' => $data['access_user_id']
      ]);

  }

  public function updateEnrolled($enrolled, $data)
  {
      return tap($enrolled)->update($data);
  }



  public function generateEnrolledCode()
  {
      $codeExists = true;
      $code = '';
      do {
          $code = helper::generateRandomString(12);
          $codeExists = Enrolled::where('code', $code)->exists();
      } while ($codeExists == true );

      return $code;
  }




}
