<?php

namespace App\Http\Controllers\front;
use App\course;
use App\course_test_result;
use App\helpers\domainHelper;
use App\Http\Controllers\Controller;
//use App\Http\Controllers\front\Controller;
use App\member;
use App\Models\CourseTrack;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\StudentSeen;
use App\Models\Test;
use App\Models\TestResult;
use App\Services\QuestionService;
use App\Services\TestAttemptsService;
use App\Services\TestResultsService;
use App\site;
use App\TermTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LessonResponseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\LessonResponse;
use function PHPUnit\Framework\isEmpty;

class StudyController extends Controller
{

  public function __construct(
      private LessonResponseService $lessonResponseService,
      private QuestionService $questionService,
      private TestResultsService $resultsService,
  )
  {

  }

    public function indexCourses(Request $request)
  {
      $enrolled = studyService()->getStudentEnrolldStudyingCoursesById( Auth::user(), $request->enrolled );
      return view('front.study.index-courses', compact('enrolled'));
  }

    public function showCourse(Request $request)
    {
        // To get course
        $course_alias = $request->course;
        $data['course'] = course::query()->whereHas('translation',function ($q) use ($course_alias){
            $q->where('alias',$course_alias);
        })->firstOrFail();

        // To get site/study_year/diploma
        $data['site'] = site::query()->findOrFail($data['course']->site_id);

        // To get course_track
        $data['course_track'] = CourseTrack::query()->where('course_id',$data['course']->id)
            ->select('*')->with('courseable')->orderBy('sort')->get();


        return view('front.content.study.show-course', compact('data'));
    }


    public function getCourseTrackLesson(Request $request)
    {
        $course = course::query()->find($request->course);

        if (!$course) {
            // Set the error message in the session
            session()->flash('fail', __('messages.not_found'));

            return response()->json([
                'html' => '',
                'htmlErrors' => view('components.admin.datatable.page-alert')->with('fail',__('messages.not_found'))->render(),
            ]);
        }


        $lesson = Lesson::query()->with(['options','teacher'])->find($request->lesson_id);
        if (!$lesson){
            session()->flash('fail', __('messages.not_found'));

            return response()->json([
                'html' => '',
                'htmlErrors' => view('components.admin.datatable.page-alert', ['fail' => __('messages.not_found')])->render(),
            ]);
        }
        else{

            // Start check if this lesson is allowed (sort)
            // The current/clicked lesson
            $lesson_sort = CourseTrack::query()->where('course_id',$lesson->course_id)
                ->where('courseable_id',$lesson->id)->where('courseable_type','lessons')->first()->sort;


            if (!$this->checkLessonOrTestAllowed($course,$lesson_sort,'lessons')){
                session()->flash('fail', __('messages.action_not_allowed_to_open'));
                return response()->json([
                    'html' => '',
                    'htmlErrors' => view('components.admin.datatable.page-alert', ['fail' => __('messages.action_not_allowed_to_open')])->render(),
                ]);
            }


            // Save seen this lesson
            StudentSeen::query()->updateOrCreate(
                [
                    'seenable_id' => $lesson->id,
                    'seenable_type' => 'lessons',
                    'student_id' => Auth::id(),
                ]
            );

            // return your view
            return response()->json([
                'html' => view('front.content.study.show-lesson-data-content', compact('lesson'))->render(),
                'htmlErrors' => ''
            ]);
        }
    }




    public function getCourseTrackTest(Request $request)
    {
        $test = Test::query()->with(['questions'=>function($q){
            $q->where('status',1)->with(['answers'=>function($q2){
                $q2->where('status',1)->with('translations');
            }]);
        }])->find($request->test_id);


        if (!$test){
            session()->flash('general_error', __('messages.not_found'));
            return response()->json([
                'html' => '',
                'htmlErrors' => view('components.admin.datatable.page-alert', ['general_error' => __('messages.not_found')])->render()
            ]);
        }

        $course = course::find($request->course);

        if (!$course){
            session()->flash('general_error', __('messages.not_found'));
            return response()->json([
                'html' => '',
                'htmlErrors' => view('components.admin.datatable.page-alert', ['general_error' => __('messages.not_found')])->render()
            ]);
        }

//        return $test->questions;
//        return $test->time_details;


        if ( $test->questions->count() == 0){
            session()->flash('general_error', __('messages.not_found'));
            return response()->json([
                'html' => '',
                'htmlErrors' => view('components.admin.datatable.page-alert', ['general_error' => __('messages.not_found')])->render()
            ]);
        }


        $testFullDuration =  Question::query()->where('status',1)->where('test_id',$test->id)
            ->sum('duration');


        // For Test Full Time
        $testTimeService = new \App\Services\TestTimeService([
            'student_id' => auth()->id(),
            'test_id' => $test->id,
            'test_duration' => $testFullDuration ?? config('domain.default_test_duration'),
        ]);
        $test->time_details = $testTimeService->startTest();


        // Note!!!!!!!!!!!!!    عشان لو الطالب دخل مرتين --> تم تأجيلها
//        if(! $test->time_details['userHasRemainTime']){
//            // user entered this quiz before, and time finished
//            // so get entered time to tell the user that he is entered this quiz at ...
//            $oldEnteredTest = \App\Models\TestTime::where('student_id', Auth::id())->where('test_id', $test->id)->select('start_time')->first();
//
//            $testTimeService->deleteTestTime();
//
//            // store failed testResult
//            $storedTestResult = testResultsService()->store([
//                'student_id' => Auth::id(),
//                'course_id' => $course->id,
//                'test_id' => $test->id,
//                'degree' =>  0,
//                'rate' => 0,
//                'percentage' => $test->percentage,
//                'locale' => app()->getLocale()
//            ]);
//
//
//            session()->flash('global_errors', __('messages.not_found'));
//
//
//            return response()->json([
//
//                'html' => ' ',
//                'htmlErrors' => view('components.front.page-alert', [
//                    'global_errors' => __('domain_messages.test_time_finished') . ' ' . $oldEnteredTest->start_time
//                ])->render()
//            ]);
//
//        }

        // Start check if this lesson is allowed (sort)
        // The current/clicked test
        $test_sort = CourseTrack::query()->where('courseable_id',$test->id)->where('courseable_type','tests')->first()->sort;

//        dd($test);

        if (!$this->checkLessonOrTestAllowed($course,$test_sort,'tests')){
            session()->flash('fail', __('messages.action_not_allowed_to_open'));
            return response()->json([
                'html' => '',
                'htmlErrors' => view('components.admin.datatable.page-alert', ['fail' => __('messages.action_not_allowed_to_open')])->render(),
            ]);
        }

        // Save seen this test
        StudentSeen::query()->updateOrCreate(
            [
                'seenable_id' => $test->id,
                'seenable_type' => 'tests',
                'student_id' => Auth::id(),
            ]
        );

        // At final return test
        return response()->json([
            'html' => view('front.content.study.show-test-content', compact('test','course'))->render(),
            'htmlErrors' => ''
        ]);

    }



    public function testResult(Request $request)
    {
        $test = Test::find($request->test);
        $course = course::find($request->course);

        if (!$course){
            if ($request->ajax()) {
                return response()->json([
                    'html' => ' ',
                    'htmlErrors' => view('components.front.page-alert', ['global_errors' => 'No Course Found'])->render()
                ]);
            }
        }

        if (!$test){
            if ($request->ajax()) {
                return response()->json([
                    'html' => ' ',
                    'htmlErrors' => view('components.front.page-alert', ['global_errors' => 'No Test Found'])->render()
                ]);
            }
        }

//
//        $testAttemptsService = new TestAttemptsService($test->attempts_count);
//        $studentAttemptsCountInTest = $testAttemptsService->getStudentAttemptsCountInTest(Auth::user(), $test->id);
//        $studentAttemptsCountInTest = 2; // tempraly
//        if (! $studentAttemptsCountInTest) {
//            $currencies = (new \App\Services\CurrencyService())->getActive();
//            return response()->json([
//                'html' => view('front.study.reserve-extra-try-content', ['enrolled' => $enrolled, 'course' => $course, 'test' => $test, 'currencies' => $currencies])->render(),
//                'htmlErrors' => view('components.front.page-alert', ['global_errors' => __('domain_messages.invalid_test_attempts_count')])->render(),
//            ]);
//        }


        // ----------------------------------------------------------------------

//         if $request->v == 'n' : means timer is over and we will submit the test without check answers
        if($request->v === 'v'){
            if (! $request->answers) {
                return response()->json([
                    'msg' => __('messages.select_marked_answers'),
                    'alert' => 'swal'
                ]);
            }

            if (empty($request->answers)) {
                return response()->json([
                    'msg' => __('messages.select_marked_answers'),
                    'alert' => 'swal'
                ]);
            }
        }



        $questions = $this->questionService->getQuestionsByIds($request->questions);
        $studentPoints = 0;
        $answers = [];
        foreach ($questions as $question)
        {
            if (isset($request->answers[$question->id])) {
                if (in_array($request->answers[$question->id], $question->correct_answers) ) {
                    $studentPoints = $studentPoints + $question->degree ; // $questionPoints;
                }

                $answers[] = [
                    'student_id' => Auth::id(),
                    'test_id' => $test->id,
                    'question_id' => $question->id,
                    'question_original_degree' => $question->degree,
                    'answer_id' => isset($request->answers[$question->id]) ? $request->answers[$question->id] : 0, // the default is user must answer all answers but mybe time is over and user didn't select some answers so we save it 0
                ];
            } else {
                if($request->v === 'v'){
                    return response()->json([
                        // 'htmlErrors' => view('components.front.page-alert', ['global_errors' => __('domain_messages.select_marked_answers')])->render()
                        'msg' => __('messages.select_marked_answers'),
                        'alert' => 'swal'
                    ]);
                }
            }
        }



        $testFullPoints = $questions->sum('degree');
        if ($testFullPoints){
            $studentDegree = ($studentPoints / $testFullPoints) * 100;
        } else {
            $studentDegree = 0;
        }


        $studentDegree = domainHelper::formatDegreeNumber($studentDegree); // number_format($studentDegree, 2);
        $studentRate = domainHelper::calculateTestRate($studentDegree);


        $storedTestResult = $this->resultsService->store([
            'student_id' => Auth::id(),
            'course_id' => $course->id,
            'test_id' => $test->id,
            'degree' =>  $studentDegree,
            'rate' => $studentRate,
            'percentage' => $test->percentage,
            'locale' => app()->getLocale()
        ]);



        // حذف التوقيت للطالب لهذا الاختبار
        $testTimeService = new \App\Services\TestTimeService([
            'student_id' => auth()->id(),
            'test_id' => $test->id,
        ]);
        $testTimeService->deleteTestTime();




        // حفظ اجابات الطالب
        foreach($answers as $key => $answer){
            $answers[$key]['test_result_id'] = $storedTestResult->id;
        }

//        return $answers;


        \App\Models\TestAnswer::insert($answers);

        $student = member::find(Auth::id());

        $currentTest = $test;

        $testResults = resultsService()->getStudentTestResults($student, $currentTest);

        // To check if the course is finished
        $this->resultsService->CheckCourseFinished($course);

        // To check if the Term is finished
        $this->resultsService->CheckTermFinished($course);


        return response()->json([
            'html' => view('front.content.study.show-test-results-content', compact('student','currentTest'))->render(),
            'htmlErrors' => ' '
        ]);

        // return redirect()->route('front.enrolls.courses.tests.result', ['test' => $test->id]);


    }


    public function showLesson(Request $request)
    {

      $enrolled = studyService()->getStudentEnrolldStudyingCourseLessonByIds( Auth::user(), $request->enrolled, $request->course, $request->lesson );

      $lesson = null;

      if ($enrolled->enrolled_terms->isNotEmpty()) {
        if ($enrolled->enrolled_terms->first()->enrolled_term_courses->isNotEmpty()){
          if ($enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course?->course_track->isNotEmpty()){
            $lesson = $enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course?->course_track->first()->courseable;
          }
        }
      }

      if (! $lesson){
          if ($request->ajax()) {
              return response()->json([
                'html' => ' ',
                'htmlErrors' => view('components.front.page-alert', ['global_errors' => 'No Lesson Found'])->render()
              ]);
          }
      }



      $course = $enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course;
      $courseTrack = $enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course->course_track->first();
      $studentSeePreviousLesson = studyService()->isStudentSeePreviousLesson(Auth::user(), $course, $courseTrack);

      if (! $studentSeePreviousLesson) {
        return response()->json([
          'html' => ' ',
          'htmlErrors' => view('components.front.page-alert', ['global_errors' => __('domain_messages.must_see_previous_lesson')])->render()
        ]);
      }



      $lesson->seen()->firstOrNew(['student_id' => Auth::id()])->save();


      if ($lesson->isDataStudyType()) {
          if ($request->ajax()) {
              return response()->json([
                'html' => view('front.study.show-lesson-data-content', ['enrolled' => $enrolled, 'lesson' => $lesson])->render(),
                'htmlErrors' => ' '
              ]);
          }

          return view('front.study.show-lesson-data-content', compact('enrolled'));
      }



      if ($lesson->isResearchStudyType()) {
          $lessonResponses = $this->lessonResponseService->getStudentLessonResponsesWithChilds(Auth::user(), $lesson);

          if ($request->ajax()) {
              return response()->json([
                'html' => view('front.study.show-lesson-research-content', ['enrolled' => $enrolled, 'lesson' => $lesson, 'lessonResponses' => $lessonResponses])->render(),
                'htmlErrors' => ' '
              ]);
          }

          return view('front.study.show-lesson-research-content', compact('enrolled'));
      }


    }

    public function storeLessonResearch(Request $request)
    {

      $validator = Validator::make($request->all(),[
        'files' => 'required|array',
        'files.*' => 'required|file|mimes:pdf,xlx,xlsx,doc,docx|max:6000',
      ]);

      if($validator->fails()) {
          if($request->ajax()) {
            return response()->json(['msg' => implode(",",$validator->messages()->all()), 'alert' => 'swal']);
          }
          return redirect()->back()->withErrors($validator);
      }




      $enrolled = studyService()->getStudentEnrolldStudyingCourseLessonByIds( Auth::user(), $request->enrolled, $request->course, $request->lesson );
      $lesson = null;

      if ($enrolled->enrolled_terms->isNotEmpty()) {
        if ($enrolled->enrolled_terms->first()->enrolled_term_courses->isNotEmpty()){
          if ($enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course?->course_track->isNotEmpty()){
            $lesson = $enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course?->course_track->first()->courseable;
          }
        }
      }

      if (! $lesson){
          if ($request->ajax()) {
              return response()->json([
                'html' => ' ',
                'htmlErrors' => view('components.front.page-alert', ['global_errors' => 'No Lesson Found'])->render()
              ]);
          }
      }

      if (! $lesson->isResearchStudyType()) {
          if ($request->ajax()) {
              return response()->json([
                'html' => ' ',
                'htmlErrors' => view('components.front.page-alert', ['global_errors' => 'No Lesson Research Found'])->render()
              ]);
          }
      }


      $course = $enrolled->enrolled_terms->first()->enrolled_term_courses->first()->course;



      $lessonResponse = new LessonResponse([
        'lesson_id' => $lesson->id,
        'description' => $request->description,
        'parent_id' => $request->parent_id
      ]);
      $lessonResponse = Auth::user()->lesson_responses()->save($lessonResponse);


      foreach ($request->file('files') as $file) {
        $lessonResponse->lesson_response_files()->create([
            'path' => $file->store('lesson_response_files')
        ]);
      }


      if($request->ajax()){
        return response()->json([
          'msg' => __('messages.added'),
          'alert' => 'swal',
          // 'redir' => true
        ]);
      }
      return redirect()->back();


    }


    public function reserveExtraAttempt(Request $request)
    {

      $validator = Validator::make($request->all(),[
          // 'enrolled' => 'integer|exists:enrolled,id',
          // 'course' => 'integer|exists:courses,id',
          // 'test' => 'integer|exists:tests,id',
          'total_pay_amount' => 'required|numeric|min:1',
          'currency_id' => 'required|integer|exists:currencies,id', // less active
          'total_pay_image' => 'required|image|max:2048|mimes:'.config('domain.pay_image_allowed_extensions'),
      ]);

      if($validator->fails()) {
          if($request->ajax()) {
            return response()->json([
              'htmlErrors' => view('components.front.page-alert', ['errors' => $validator->errors()])->render()
            ]);
          }
          return redirect()->back()->withErrors($validator);
      }


      // 01 check less : check test belongs to this student
      // if (! $student->enrolles()?????? ){
      //     Log::channel('domain')->error('TestQuestionController, '. '(function) reserveExtraTry, ' .'Test dosnt belong to student');
      //     if($request->ajax()){
      //       return response()->json([
      //         'htmlErrors' => view('components.front.page-alert', ['global_errors' => __('messages.error_data')])->render()
      //       ]);
      //     }
      // }


      // 02 save

      // 03 message that will review in admin
      if($request->ajax()){
        return response()->json([
          'htmlSuccess' => view('components.front.page-alert', ['global_errors' => __('messages.error_data')])->render()
        ]);
      }



    }

    public function studyStop()
  {
      return view('front.study.study-stop');
  }

    public function checkLessonOrTestAllowed($course,$elementSort,$element_type)
    {
        // All course_track sorted
        $course_track = CourseTrack::query()->where('course_id',$course->id)->select('*')->orderBy('sort')->get();

        $elementBefore = null;
        foreach ($course_track as $key => $element) {
            if ($element->sort == $elementSort) {
                if ($key > 0) {
                    $elementBefore = $course_track[$key - 1];
                    break;
                }
            }
        }

        if ($elementBefore) {   // Check if there is a lesson/test before this lesson/test
            // check if the lesson/test before is seen
            $check_lesson_seen = StudentSeen::query()->where('student_id',Auth::id())
                ->where('seenable_id',$elementBefore->courseable_id)->where('seenable_type',$elementBefore->courseable_type)->first();

            if (!$check_lesson_seen){
                return false;
            }
        }
        return true;
    }



} //end of class
