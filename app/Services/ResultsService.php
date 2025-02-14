<?php

namespace App\Services;
use App\helpers\domainHelper;
use DB;
use App\MemberCoursesResult;
use App\MemberSitesResult;
use App\MemberTermsResult;
use App\Services\TermService;
use Illuminate\Support\Facades\Log;

class ResultsService
{

  private $user;
  private $course;
  private $site;

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function setCourse($course)
  {
      $this->course = $course;
      return $this;
  }

  public function setTerm($term)
  {
      $this->term = $term;
      return $this;
  }

  public function setSite($site)
  {
      $this->site = $site;
      return $this;
  }




  // courses results
  public function getUserSuccesseCources()
  {
      // الاختبار الاعلى درجة فى كل دورة او لدورة محددة
      // اعلى درجة - اعلى تقدير - عدد الاختبارات لهذه الدورة
      if(! $this->user) {
        return [];
      }
      $userId = $this->user->id;

      $courseId = null;
      if($this->course) {
        $courseId = $this->course->id;
      }

      $currentLocale = app()->getlocale();

      $sql = "Select
          course_site.site_id, course_tests_results.course_id, count(course_tests_results.course_id) as tests_count,
          max(degree) as test_degree, max(rate) as test_rate, course_tests_results.created_at, sites.new_flag
          FROM `course_tests_results`
          JOIN course_site on course_tests_results.course_id = course_site.course_id
          JOIN sites on course_tests_results.site_id = sites.id
          WHERE course_tests_results.locale = '$currentLocale'
          AND course_tests_results.user_id = $userId ";

      if($courseId){
          $sql = $sql .  " and course_tests_results.course_id = $courseId ";
      }

      $sql = $sql . " GROUP by course_site.site_id, course_tests_results.course_id";

      return DB::select($sql);

  }

  public function getUserSuccesseCourcesFullInfo()
  {
        // الاختبار الاعلى درجة فى كل دورة او لدورة محددة
        // بعد اضافة بيانات اخرى من السجل الاعلى درجة
        $info = $this->getUserSuccesseCources();



        foreach ($info as $test) {
          $exactSuccessRecord = DB::Table('course_tests_results')
            ->where('user_id', $this->user->id)
            ->where('course_id', $test->course_id)
            // ->where('degree', $test->test_degree) // we can serach for '92.50', but for '2.78' it gives null
            // ->where('rate', $test->test_rate)
            ->orderBy('degree', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->select('id', 'term_id','course_degree', 'course_rate', 'locale', 'code', 'no_test', 'created_at')
            ->first();

          if($exactSuccessRecord){
            $test->test_id = $exactSuccessRecord->id;
            $test->term_id = $exactSuccessRecord->term_id;
            $test->test_course_degree = $exactSuccessRecord->course_degree;
            $test->test_course_rate = $exactSuccessRecord->course_rate;
            $test->test_locale = $exactSuccessRecord->locale;
            $test->test_code = $exactSuccessRecord->code;
            $test->test_no_test = $exactSuccessRecord->no_test;
            $test->test_created_at = $exactSuccessRecord->created_at;
            $test->site_new_flag = $test->new_flag;
          } else {
            Log::channel('userresults')->info($this->user->id . '-' . $test->course_id . '-' . $test->test_degree . '-' . $test->test_rate);
          }

      }

      return $info;

  }

  public function deleteUserCourseResult()
  {
      $courseId = null;
      if($this->course) {
        $courseId = $this->course->id;
      }

      MemberCoursesResult::where('user_id', $this->user->id)->where('locale', app()->getlocale())
        ->when($courseId, function($q) use($courseId){
            return $q->where('course_id', $courseId);
        })
        ->delete();
  }

  public function saveUserCourseResult($userSuccesseCourceFullInfo)
  {



      $insertMany = [];
      foreach($userSuccesseCourceFullInfo as $test){
          // $trem_id = (new TermService())->getTermIdBySiteByCourse($test->site_id, $test->course_id);
          // $trem_id = getTirm($site_id)

          $insertMany[] = [
            'user_id' => $this->user->id,
            'site_id' => $test->site_id,
            'course_id' => $test->course_id,
            'term_id' => $test->term_id,
            'locale' => $test->test_locale,
            'tests_count' => $test->tests_count,
            'test_no_test' => $test->test_no_test,
            'test_course_degree' => $test->test_course_degree,
            'test_degree' => $test->test_degree,
            'test_course_rate' => $test->test_course_rate,
            'test_rate' => $test->test_rate,
            'test_id' => $test->test_id,
            'test_locale' => $test->test_locale,
            'test_code' => $test->test_code,
            'test_created_at' => $test->test_created_at,
            'site_new_flag' => $test->site_new_flag
          ];
      }

      MemberCoursesResult::insert( $insertMany );

  }

  public function saveFinalUserCourseResult()
  {
      DB::beginTransaction();
      try {

        $finalTestResults = $this->getUserSuccesseCourcesFullInfo();
        $this->deleteUserCourseResult();
        $this->saveUserCourseResult($finalTestResults);

        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        Log::channel('userresults')->info('error saveFinalUserCourseResult :' . ' user_id: ' . $this->user->id . ' course_id: ' . $this->course->id );
      } catch (\Error $e) {
        DB::rollback();
        Log::channel('userresults')->info('error saveFinalUserCourseResult :' . ' user_id: ' . $this->user->id . ' course_id: ' . $this->course->id );
      }

  }

  public function getFinalUserCourseResult()
  {
      if(! $this->user) {
        return [];
      }
      $userId = $this->user->id;

      $courseId = null;
      if($this->course) {
        $courseId = $this->course->id;
      }

      // elequent
      $finalResults = MemberCoursesResult::where('user_id', $userId)
        ->with('site_translation:id,site_id,name as site_title,slug as site_title')
        ->with('course_translation:id,course_id,name as course_title,alias as course_title')
        ->groupBy('course_id','locale');

      if($courseId){
        $finalResults = $finalResults->where('course_id', $courseId);
      }

      return $finalResults->groupBy('course_id','locale')->get();


  }

  public function insertAllUsersCoursesResults()
  {
      // تستخدم لادخال نتائج جميع المستخدمين للمرة الاولى
      // loop throw all users and store final results for each one then in evry test we save the final result again
      // DB::table('members')->where('id', '<=', '220000')->where('id', '>=', '190000')->chunkById(20, function ($members){
      //     foreach ($members as $member) {
      //         $this->setUser($member)->saveFinalUserCourseResult();
      //     }
      // });
  }





  // term results
  public function saveUserTermsResults()
  {
      $successedTestOfTerm = (new \App\Services\TermTestResultService())
        ->getUserLargestDegreeOfTerm($this->term, $this->user, app()->getLocale());

      $data = [
        'user_id' => $this->user->id,
        'site_id' => $successedTestOfTerm->site_id,
        'term_id' => $successedTestOfTerm->term_id,
        'test_locale' => $successedTestOfTerm->locale,
        'courses_count' => $this->term->courses()->count(),
        'tests_count' => $this->term->term_results()->count(),
        'user_successed' => $successedTestOfTerm->degree >= pointOfSuccess(),
        'test_no_test' => $successedTestOfTerm->no_test,
        'test_degree' => $successedTestOfTerm->degree,
        'test_rate' => $successedTestOfTerm->rate,
        'test_id' => $successedTestOfTerm->id,
        'test_code' => $successedTestOfTerm->code,
        'test_created_at' => $successedTestOfTerm->updated_at,
      ];

      MemberTermsResult::create( $data );
  }

  public function deleteUserTermsResult()
  {
      MemberTermsResult::where('user_id', $this->user->id)->where('test_locale', app()->getlocale())->delete();
  }

  public function saveFinalUserTermsResults()
  {

        DB::beginTransaction();
        try {

          $this->deleteUserTermsResult();
          $this->saveUserTermsResults();

          DB::commit();
        } catch (\Exception $e) {
          DB::rollback();
          Log::channel('userresults')->info('error saveFinalUserTermsResults :' . $e->getMessage() . ' user_id: ' . $this->user->id . ' term_id: ' . $this->term->id );
        } catch (\Error $e) {
          DB::rollback();
          Log::channel('userresults')->info('error saveFinalUserTermsResults :' . $e->getMessage() . ' user_id: ' . $this->user->id . ' term_id: ' . $this->term->id );
        }

  }




  // sites results
  public function getFinalUserSiteResult()
  {

      $siteCompleted = $this->site->isAllExamsOpened();
      $siteCoursesCount = $this->site->validCourses('count', app()->getlocale());
      $userTestsCount = $this->user->testsCount($this->site->id, app()->getlocale());
      $userFinishedSite = $siteCoursesCount == $userTestsCount;
      $userSuccessedInSite = 0;
      if ($siteCompleted && $userFinishedSite){
        $userSuccessedInSite = $this->user->isSuccessedInSite($this->site->id, app()->getlocale());
      }
      $userMaxTestDatetime = $this->user->maxCreatedAtOfSite($this->site->id, app()->getlocale()); //  $this->user->test_results()->where('site_id', $this->site->id)->max('created_at');
      $userSiteDegree = $this->user->siteDegree($this->site->id, app()->getlocale());
      $closedExamsCount = $this->site->examsStillClosed('count', app()->getlocale());
      $siteNewFlag = $this->site->new_flag;

      return [
        'user_id' => $this->user->id,
        'site_id' => $this->site->id,
        'site_completed' => $siteCompleted,
        'courses_count' => $siteCoursesCount,
        'user_tests_count' => $userTestsCount,
        'user_finished_site' => $userFinishedSite,
        'user_successed' => $userSuccessedInSite,
        'user_max_test_datetime' => $userMaxTestDatetime,
        'user_site_degree' => $userSiteDegree,
        'closed_exams_count' => $closedExamsCount,
        'site_new_flag' => $siteNewFlag,
        'locale' => app()->getlocale(),
        'created_at' => now(),
        'updated_at' => now(),
      ];

  }

  public function getFinalUserSitesResults()
  {
      $sitesResults = [];
      $sites = \App\site::select('id','new_flag')->get();

      foreach ($sites as $site) {
        if ($this->user->testsCount($site->id, app()->getlocale()) > 0 ){
          $sitesResults[] = $this->setSite($site)->getFinalUserSiteResult();
        }
      }

      return $sitesResults;

  }

  public function deleteUserSiteResult()
  {
      MemberSitesResult::where('user_id', $this->user->id)->where('locale', app()->getlocale())->delete();
  }

  public function saveUserSitesResults()
  {
      MemberSitesResult::insert( $this->getFinalUserSitesResults() );
  }

  public function saveFinalUserSitesResults()
  {
      DB::beginTransaction();
      try {

        $this->deleteUserSiteResult();
        $this->saveUserSitesResults();

        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        Log::channel('userresults')->info('error saveFinalUserSitesResults :' . ' user_id: ' . $this->user->id . ' course_id: ' . $this->site->id );
      } catch (\Error $e) {
        DB::rollback();
        Log::channel('userresults')->info('error saveFinalUserSitesResults :' . ' user_id: ' . $this->user->id . ' course_id: ' . $this->site->id );
      }

  }

  public function insertAllUsersSitesResults()
  {
      // تستخدم لادخال نتائج جميع المستخدمين للمرة الاولى
      // loop throw all users and store final results for each one then in evry test we save the final result again

      // ini_set('memory_limit', '2048M');
      // ini_set('max_execution_time', 600);
      //
      // DB::Table('a')->where('user_id', '>=', 0)->where('user_id', '<=', 5000)
      //         ->chunkById(10, function ($members) {
      //     foreach ($members as $member) {
      //         $user = \App\member::where('id', $member->user_id)->withTrashed()->first();
      //         if ( $user){
      //           $this->setUser($user)->saveFinalUserSitesResults();
      //         }
      //
      //     }
      // });
      // dd('Done 07');


      // deleted users
      // DB::Table('members')->whereNotNull('deleted_at')->select('id','deleted_at')->chunkById(100, function ($members) {
      //     foreach ($members as $member) {
      //         $user = \App\member::where('id', $member->id)->withTrashed()->first();
      //         $this->setUser($user)->saveFinalUserSitesResults();
      //     }
      // });
      // dd('Done 01');


  }




    // نتائج الطالب فى اختبار
    public function getStudentTestResults($student, $test)
    {
        return $student->test_results()->where('test_id', $test->id)->get();
    }

    // اختبارات الطالب لمادة معينة
    public function getStudentCourseTestsResults($student, $course)
    {
        return $course->course_track()->test()
            ->with(['courseable.test_results' => function($q) use($student){
                $q->where('student_id', $student->id)->orderBy('degree', 'desc'); // max degree at the first
            }])->get();

    }

    // حساب نتيجة الطالب فى مادة معينة
    public function getStudentEnrolledTermCourseResult($enrolledTermCourse, $student = null, $course = null, $studentCourseResults = [])
    {

        // معادلة
        if ($enrolledTermCourse->isEqualRealStudy()){
            return (object) [
                'isSuccessed' => true,
                'isFailed' => false,
                'degree' => domainHelper::formatDegreeNumber($enrolledTermCourse->degree),
                'rate' => domainHelper::calculateTestRate($enrolledTermCourse->degree),
            ];
        }

        // دراسة فعلية
        if ($studentCourseResults) {
            $results = $studentCourseResults;
        } else {
            $results = $this->getStudentCourseTestsResults($student, $course);
        }


        $courseTestsCount = $results->count();
        $courseHasTests = $courseTestsCount ? true : false;

        $studentTestedCount = 0;
        $studentUnTestedCount = 0;

        $successedTestsCount = 0;
        $failedTestsCount = 0;

        $isSuccessed = false;
        $isFailed = false;

        $finishedAllCourseTests = false;
        $courseDegree = 0;


        foreach ($results as $courseTrackTest)
        {
            $test = $courseTrackTest->courseable;
            if ($test){

                // الطالب اختبر وله نتيجة
                if ($test->test_results->isNotEmpty()) {
                    $studentTestedCount = $studentTestedCount + 1;

                    $testResult = $test->test_results->first(); // this is max degree
                    $test->test_result = $testResult;

                    // نتيجة الاختبار
                    if ($testResult->isSuccessed()) {
                        $successedTestsCount = $successedTestsCount + 1;
                    } else {
                        $failedTestsCount = $failedTestsCount + 1;
                    }

                    // الدرجة وفقا لنسبة الاختبار
                    $courseDegree = $courseDegree + ($testResult->degree * ($test->percentage/100));
                } else {
                    $studentUnTestedCount = $studentUnTestedCount + 1;
                }


                // الدورة لها اختبارات هل انهاها جميعا
                if ($courseHasTests) {
                    $finishedAllCourseTests = ($studentTestedCount == $courseTestsCount);
                }

                // هل الطالب انهى كل محاولات الاختبار سيتم وضعها فى الاعتبار
                // $testAttemptsService = new TestAttemptsService($test->attempts_count);
                // $studentAttemptsCountInTest = $testAttemptsService->getStudentAttemptsCountInTest(Auth::user(), $test->id);

                // الطالب انهى كل الاختبارات ونجح فى كل الاختبارات
                if ($courseHasTests && $finishedAllCourseTests) {
                    if ($studentTestedCount == $successedTestsCount){
                        $isSuccessed = true;
                    }
                    if ($studentTestedCount != $successedTestsCount){
                        $isFailed = true;
                    }
                }

            }
        }



        return (object) [
            'courseHasTests' => $courseHasTests,
            'courseTestsCount' => $courseTestsCount,
            'studentTestedCount' => $studentTestedCount,
            'studentUnTestedCount' => $studentUnTestedCount,
            'successedTestsCount' => $successedTestsCount,
            'failedTestsCount' => $failedTestsCount,
            'finishedAllCourseTests' => $finishedAllCourseTests,
            'isSuccessed' => $isSuccessed,
            'isFailed' => $isFailed,
            'degree' => domainHelper::formatDegreeNumber($courseDegree),
            'rate' => domainHelper::calculateTestRate($courseDegree),
        ];


    }


    // نتائج الطالب فى مادة معينة
    public function getStudentCourseResults($student, $courseId)
    {
        return $student->enrolled_term_courses()->where('course_id', $courseId)->orderBy('degree', 'desc')->get();
    }
    // نتيجة الطالب فى مادة معينة
    public function getStudentCourseResult($student, $courseId)
    {
        return $student->enrolled_term_courses()->where('course_id', $courseId)->orderBy('degree', 'desc')->first();
    }


    // نتيجة الطالب النهائية للتيرم
    public function getStudentEnrolledTermResult($enrolledTerm)
    {
        $results = $enrolledTerm->enrolled_term_courses()->select('degree')->get();

        $termDegree = 0;
        if ($results->isNotEmpty()){
            $termDegree = $results->sum('degree') / $results->count();
        }

        return (object) [
            'degree' => domainHelper::formatDegreeNumber($termDegree),
            'rate' => domainHelper::calculateTestRate($termDegree),
        ];
    }

    // نتيجة الطالب النهائية للالتحاق
    public function getStudentEnrolledResult($enrolled)
    {
        $results = $enrolled->enrolled_terms()->select('degree')->get();

        $enrolledDegree = 0;
        if ($results->count()){
            $enrolledDegree = $results->sum('degree') / $results->count();
        }

        return (object) [
            'degree' => domainHelper::formatDegreeNumber($enrolledDegree),
            'rate' => domainHelper::calculateTestRate($enrolledDegree),
        ];
    }

    // نتائج الطالب فى كل الالتحاقات
    // صفحة شهاداتى
    public function getStudentEnrolledsResults($student)
    {
        return \App\Models\Enrolled::withDetails()
            ->with('enrolled_terms.term')
            ->with('enrolled_terms.enrolled_term_courses.course')
            ->where('student_id', $student->id)
            ->get();
    }


    // حفظ نتيجة المادة
    public function saveEnrolledTermCourseResult($enrolledTermCourse, $courseResult)
    {
        $enrolledTermCourse->degree = $courseResult->degree;
        $enrolledTermCourse->rate = $courseResult->rate;
        $enrolledTermCourse->save();
    }

    // حفظ نتيجة التيرم
    public function saveEnrolledTermResult($enrolledTerm, $termResult = null)
    {
        if (! $termResult) {
            $termResult = $this->getStudentEnrolledTermResult($enrolledTerm);
        }

        $enrolledTerm->degree = $termResult->degree;
        $enrolledTerm->rate = $termResult->rate;
        $enrolledTerm->save();
    }

    // حفظ نتيجة الالتحاق
    public function saveEnrolledResult($enrolled, $enrolledResult = null)
    {
        if (! $enrolledResult) {
            $enrolledResult = $this->getStudentEnrolledResult($enrolled);
        }

        $enrolled->degree = $enrolledResult->degree;
        $enrolled->rate = $enrolledResult->rate;
        $enrolled->save();
    }


    // حفظ وتعديل الدرجات والحالة النهائية للمادة
    public function saveEnrolledTermCourseFinalResult($student, $enrolledTermCourse, $course)
    {

        // نتيجة الدورة
        $courseResult = $this->getStudentEnrolledTermCourseResult($enrolledTermCourse, $student, $course);


        // لو دراسة فعليه احفظ النتيجة لو معادلة فالنتيجة تم حفظها مسبقا عند تسجيل المعادلة
        if ($enrolledTermCourse->isExactRealStudy()){
            $this->saveEnrolledTermCourseResult($enrolledTermCourse, $courseResult);
        }

        // تعديل الدراسة للمادة
        enrolledStatusService()->updateEnrolledTermCourseStudyStatus($enrolledTermCourse, $courseResult);

        // تعديل النجاح للمادة
        enrolledStatusService()->updateEnrolledTermCourseSuccessStatus($enrolledTermCourse, $courseResult);

    }



    public function saveEnrolledAndTermFinalResult($student, $enrolled, $enrolledTerm)
    {

        // درجة التيرم وحفظها
        $this->saveEnrolledTermResult($enrolledTerm);

        // درجة الالتحاق وحفظها
        $this->saveEnrolledResult($enrolled);


        $sectionCertificateService = new \App\Services\SectionCertificateService();
        $sectionCertificate = $sectionCertificateService->getActiveSectionCertificateBySectionAndCertificate(
            $enrolled->section_id, $enrolled->certificate_id
        );

        // تعديل الدراسة للالتحاق والتيرم
        enrolledStatusService()->updateEnrolledAndTermStudyStatus($student, $enrolled, $enrolledTerm, $sectionCertificate );

        // تعديل النجاح للالتحاق والتيرم
        enrolledStatusService()->updateEnrolledAndTermSuccessStatus($student, $enrolled, $enrolledTerm, $sectionCertificate );

        return true;

    }





}
