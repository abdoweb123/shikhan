<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\libraries\_commonfn;
use App\libraries\Helpers;

use App\course;

use Validator;
use App;
use Auth;
use Session;
use App\course_test_result;
use App\course_test;
use App\MemberQuizTime;
use App\Services\CourseTestResultService;
use App\Services\GlobalService;
use App\Services\QuizTimeService;
use DB;
use Illuminate\Support\Facades\Log;

class quiz extends Controller
{
    // use UserHasExtraTray;
    private $courseTestResultService;
    private $globalService;


    public function __construct(CourseTestResultService $courseTestResultService, GlobalService $globalService)
    {
        $this->courseTestResultService = $courseTestResultService;
        $this->globalService = $globalService;
    }

    public function index(Request $request)
    {

        $site_alias = \Route::input('site');
        $course_alias = \Route::input('course');
        $data = $this->data($request,'courses');


        $validator = $this->validatorIndex(['site_alias' => $site_alias, 'course_alias' => $course_alias]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data['site'] = App\site::where('status', 1)->whereTranslation('slug', $site_alias)
          ->select('id','title','parent_id','logo','new_flag','conditions','likes_count','views_count','short_link','sort','status','deleted_at')
          ->firstOrFail();

        $data['course'] = $data['site']->courses()->where(['status' => 1])->whereTranslation('alias', $course_alias)
          ->select('courses.id','title','date','exam_at','exam_approved','max_trys','sort','status','new_flag','visual_test','question_period'
            ,'logo','courses.site_id','deleted_at')
          ->firstOrFail();

        $data['term'] = $data['course']->getTirm($data['site']->id);

        $data['questions_old'] = $data['course']->questions()->where(['status' => 1])
          ->whereRelation('translations', 'locale','=',app()->getlocale())
          ->orderBy('sequence','ASC')
          ->get();


        if($data['questions_old']->isEmpty()){
          $data['errors'] = [ __('trans.quiz_not_ready') ];
          return view('front.content.courses.quiz-errors', $data);
        }





        // default values
        $data['userCanOpenTest'] = false;
        $data['userHasTrays'] = false;
        $data['trays'] = 0;
        $data['quiz'] = [];


        // 120 minutes
        // $data['userCanOpenTest'] = $this->userCanOpenTest($data['course']->id, Auth::guard('web')->id() );
        // $data['minutesToOpenTest'] = $this->courseTestResultService->minutesToOpenTest;
        //
        // if (ourAuth()){
        //   $data['userCanOpenTest'] = true;
        // }
        //
        // if (! $data['userCanOpenTest']){
        //   // return view('front.content.courses.quiz',$data);
        //   $data['errors'] = [ __('message.test_again_after', [ 'period' => $data['minutesToOpenTest'] ]) ];
        //   return view('front.content.courses.quiz-errors', $data );
        // }
        $data['userCanOpenTest'] = true;
        // -------------------------------




        $userService = new \App\Services\UserService();
        $userCanOpenTestInThisDate = $userService->setUser(auth()->user())->setCourse($data['course'])
          ->setDate(date('Y-m-d'))
          ->userCanOpenTestInThisDate();

        if (ourAuth()){
          $userCanOpenTestInThisDate = true;
        }

        if(! $userCanOpenTestInThisDate){
          $data['errors'] = [ __('core.invalid_quiz_count_per_day') ];
          return view('front.content.courses.quiz-errors', $data );
        }




        $data['userGetXtraTray'] = false;
        $data['trays'] = maxTests(); // helpers/core
        $extraTraysService = new App\Services\ExtraTrays([
          'user' => Auth::user(),
          'site_id' =>  $data['site']->id,
          'course_id' => $data['course']->id,
          'locale' => app()->getlocale()
        ]);
        $data['userGetXtraTray'] = $extraTraysService->getUserXtraTrays()['userHasExtraTrays'];
        $data['trays'] = $extraTraysService->getUserXtraTrays()['extraTrays'];
        $data['trays'] = $data['trays'] < maxTests() ? maxTests() : $data['trays'];

        $userTrays = Auth::guard('web')->user()->test_results->where('course_id', $data['course']->id )->count();
        $data['userTrays'] = $userTrays;
        $data['userHasTrays'] = $userTrays < $data['trays'];
        // if (! $data['userHasTrays']){
        //   return view('front.content.courses.quiz', $data);
        // }

        if (ourAuth()){
          $data['userHasTrays'] = true;
          $data['userTrays'] = 200;
        }

        if(! $data['userHasTrays']){
          $data['errors'] = [ __('core.invalid_quiz_count') ];
          return view('front.content.courses.quiz-errors', $data );
        }



        // forQuizeTime
        $quizTimeService = new QuizTimeService([
            'user_id' => auth()->id(),
            'site_id' => $data['site']->id,
            'course_id' => $data['course']->id,
            'question_period' => $data['course']->question_period,
            'questions_count' => $data['questions_old']->count()
        ]);
        $data['quiz'] = $quizTimeService->startQuiz();


        if(! $data['quiz']['userHasRemainTime']){
            // user entered this quiz before and time finished
            // so get entered time to tell the user that he is entered this quiz at ...
            $data['enteredQuize'] = MemberQuizTime::where('user_id', Auth::id())->where('course_id', $data['course']->id)->select('start_time')->first();

            $quizTimeService->deleteQuizTime();

            // hash this cod because we will not save any test with result 0
            // and we will return code 1, 2, 3 back if we want to save any test as it is
            // 1
            // $this->createFaildCourseTestResult([
            //       'no_test' => $userTrays + 1,
            //       'user_id' => Auth::guard('web')->id(),
            //       'site_id' => $data['site']->id,
            //       'course_id' => $data['course']->id,
            //       'term' => $data['term']->id
            // ]);

            // 2
            // save user course results
            // $this->saveFinalUserCourseResult(auth()->user(), $data['course']);

            // 3
            // save user site results
            // $this->saveFinalUserSitesResults(auth()->user());
        }


        return view('front.content.courses.quiz',$data);

    }

    public function store(Request $request)
    {


        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');


        $validator = $this->validatorStore(['site_alias' => $site_alias, 'course_id' => $course_id]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }



        $site = App\site::where('status', 1)->whereTranslation('slug', $site_alias)->firstOrFail();
        $course = $site->courses()->where(['courses.id' => $course_id, 'status' => 1])->firstOrFail();
        $trem = $course->getTirm($site->id);




        // 120 minutes
        $userCanOpenTest = $this->userCanOpenTest($course->id, Auth::guard('web')->id());
        $minutesToOpenTest = $this->courseTestResultService->minutesToOpenTest;
        if (ourAuth()){
          $userCanOpenTest = true;
        }

        if (! $userCanOpenTest){
          $data['errors'] = [ __('message.test_again_after', [ 'period' => $minutesToOpenTest ]) ];
          return view('front.content.courses.quiz-errors', $data );
          // return redirect()->route('courses.quiz',['site' => $site_alias,'course' => $course_alias]);
        }
        // -------------------------------




        // $userService = new \App\Services\UserService();
        // $userCanOpenTestInThisDate = $userService->setUser(auth()->user())->setCourse($course)->setDate(date('Y-m-d'))
        //   ->userCanOpenTestInThisDate();
        //
        // if (ourAuth()){
        //   $userCanOpenTestInThisDate = true;
        // }
        //
        // if(! $userCanOpenTestInThisDate){
        //   $data['errors'] = [ __('core.invalid_quiz_count_per_day') ];
        //   return view('front.content.courses.quiz-errors', $data );
        // }
        $userCanOpenTestInThisDate = true;






        $questions = $course->questions()->where(['status' => 1])->orderBy('sequence','ASC')->get();

        $user = $request->user();
        // $no_test = $user->test_results()->where('course_id',$course->id)->count('no_test');
        $no_test = $this->getUserCourseTestsCount($course->id, app()->getlocale());



        $trays = maxTests(); // helpers/core
        $extraTraysService = new App\Services\ExtraTrays([
          'user' => Auth::user(),
          'site_id' =>  $site->id,
          'course_id' => $course->id,
          'locale' => app()->getlocale()
        ]);
        $trays = $extraTraysService->getUserXtraTrays()['extraTrays'];
        $trays = $trays < maxTests() ? maxTests() : $trays;





        $data['userHasTrays'] = $no_test < $trays;
        if ($no_test >=  $trays){
            return back()->withErrors(['error' => __('core.invalid_quiz_count')]);
        }





        $rules = [] ;
        foreach ($questions as $row) {
            $rules['answers.'.$row->id] = [Rule::requiredIf($row->required)];
            switch ($row->type) {
                case 'true_false':
                    $rules['answers.'.$row->id][] = 'boolean';
                break;
                case 'range':
                    $rules['answers.'.$row->id][] = 'between:'.$row['options']['min'].','.$row['options']['max'] ;
                break;
                case 'drop_list':
                    $answer_ids = $row->answers()->where('status',1)->orderBy('sequence','ASC')->select('id')->pluck('id')->toArray();
                    if ($row->correct_answer > 1) {
                        $rules['answers.'.$row->id.'.*'] = [Rule::in($answer_ids)] ;
                    } else {
                        $rules['answers.'.$row->id][] = Rule::in($answer_ids) ;
                    }
                break;
                default:
                    $answer_ids = $row->answers()->where('status',1)->orderBy('sequence','ASC')->select('id')->pluck('id')->toArray();
                    $rules['answers.'.$row->id][] = Rule::in($answer_ids) ;
                break;
            }
        }




        $validator = Validator::make($request->all(),$rules);
        //  $request->v = 'v' . all answers must answerd
        //  $request->v = 'n' . dont validate all answers must answerd . came from timer may be user doesn't answered all questions_old buth the time is ended
        if($request->v === 'v'){
          if ($validator->fails()) {
              return redirect()->back()->withInput()->withErrors($validator);
          }
        }





        $tests = [] ;
        $correct_answers = 0;
        foreach ($questions as $key => $row){
            if (isset($request->answers[$row->id])) {
                if (count($row->correct_answer) > 1 && is_array($request->answers[$row->id])) {
                    $count = 0 ;
                    foreach ($row->correct_answer as $c) {
                        $count += in_array($c,$request->answers[$row->id]) ? 1 : 0 ;
                    }
                    $correct_answers += ( $count / count($row->correct_answer) ) * $row->degree ;
                } else {
                    $correct_answers += in_array($request->answers[$row->id],$row->correct_answer)  ? $row->degree : 0;
                }

                $qAnswers = is_array($request->answers[$row->id]) ? $request->answers[$row->id] : [$request->answers[$row->id]] ;
                // array_map('intval',$request->answers[$row->id]) : [intval($request->answers[$row->id])];
                foreach ($qAnswers as $qAnswer) {
                  $tests[] = [
                      // 'no_test' => intval($no_test + 1),
                      // 'user_id' => $user->id,
                      // 'course_id' => $course->id,
                      'question_id' => $row->id,
                      'answer_id' => intval($qAnswer),
                      'date' => date('Y-m-d'),
                  ];
               }
            }
        }


        $sumDegree = $questions->sum('degree');
        if ($sumDegree){
          $courseDegree = round( ( $correct_answers / $sumDegree ) * 100 , 2 );
        } else {
          $courseDegree = 0;
        }

        $degree = $courseDegree;
        // add zoom points --------------------------------------------
          $extraPointsService = new \App\Services\ExtraPointsService(['user' => auth()->user(), 'course' => $course]);
          $attandeDegree = $extraPointsService->calculateExtraPonits();
          $degree = $degree + $attandeDegree;
          if($degree > 100) { $degree = 100;}
        // --------------------------------------------------------


        // $courseRate = $courseDegree >= 90 && $courseDegree <= 100 ? 5 : ($courseDegree >= 80 && $courseDegree < 90 ? 4 : ($courseDegree >= 70 && $courseDegree < 80 ? 3 : ($courseDegree >= 60 && $courseDegree < 70 ? 2 : ($courseDegree >= 50 && $courseDegree < 60 ? 1 : 0))));
        // $rate = $degree >= 90 && $degree <= 100 ? 5 : ($degree >= 80 && $degree < 90 ? 4 : ($degree >= 70 && $degree < 80 ? 3 : ($degree >= 60 && $degree < 70 ? 2 : ($degree >= 50 && $degree < 60 ? 1 : 0))));
        $courseRate = calculateCourseRate($courseDegree);
        $rate = calculateCourseRate($degree);



        // $no_test = $user->test_results()->where('course_id',$course->id)->count();
        $no_test = $this->getUserCourseTestsCount($course->id, app()->getlocale());
        if ($no_test >=  $trays) {
            return back()->withErrors(['error' => __('core.invalid_quiz_count')]);
        }



        // dont save test if user get 0
        if ($degree == 0) {
          // forQuizeTime
          // delete quize time record of current test
          $quizTimeService = new QuizTimeService([
              'user_id' => auth()->id(),
              'site_id' => $site->id,
              'course_id' => $course->id,
              'question_period' => $course->question_period,
              'questions_count' => $questions->count()
          ]);
          $quizTimeService->deleteQuizTime();

            Session(['degree'=>0]);
            Session(['rate'=>0]);

          return redirect()->route('quiz_result',['site' => $site_alias,'course' => $course->alias])->with('success', __('core.valid_quiz_count',['degree' => $degree]));

        }




        $test_result = [
            'no_test' => intval($no_test + 1),
            'user_id' => $user->id,
            'site_id' => $site->id,
            'course_id' => $course->id,
            'term_id' => $trem->id,
            'course_degree' => $courseDegree,
            'degree' => $degree,
            'course_rate' => $courseRate,
            'rate' => $rate,
            'locale' => app()->getLocale(),
            'flag' => 0,
            'status' => 1,
            'created_by' => 0,
        ];
        $coursTestsResult = $course->test_results()->create($test_result);





        // forQuizeTime
        // delete quize time record of current test
        $quizTimeService = new QuizTimeService([
            'user_id' => auth()->id(),
            'site_id' => $site->id,
            'course_id' => $course->id,
            'question_period' => $course->question_period,
            'questions_count' => $questions->count()
        ]);
        $quizTimeService->deleteQuizTime();














        foreach ($tests as $key => $test) {
          $tests[$key]['course_test_result_id'] = $coursTestsResult->id;
        }

        $course->tests()->createMany($tests);

        Session(['degree'=>$degree]);
        Session(['rate'=>$rate]);
        Session(['course_test_result_id'=>$coursTestsResult->id]);
        Session(['no_test'=>$coursTestsResult->no_test]);
        if($attandeDegree){
          $request->session()->flash('attende', 'true');
        }


        // randome code for every test
        $coursTestsResult->code = $this->generateCourseCode();
        $coursTestsResult->save();






        // save user course results
        $this->saveFinalUserCourseResult(auth()->user(), $course);

        // save user site results
        $this->saveFinalUserSitesResults(auth()->user());

        return redirect()->route('quiz_result',['site' => $site_alias,'course' => $course->alias])->with('success', __('core.valid_quiz_count',['degree' => $degree]));

    }



    public function indexTerm(Request $request)
    {

        $site_alias = \Route::input('site');
        $term_id = \Route::input('term');
        $data = $this->data($request,'courses');

        $validator = $this->validatorTerm(['site_alias' => $site_alias, 'term' => $term_id]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $data['site'] = App\site::where('status', 1)->whereTranslation('slug', $site_alias)
          ->select('id','title','parent_id','logo','new_flag','conditions','likes_count','views_count','short_link','sort','status','deleted_at')
          ->firstOrFail();


        $data['course'] = $data['site']->terms()->where(['id' => $term_id, 'status' => 1])
            ->select('terms.id','title','exam_approved','exam_at','max_trys','sort','status','question_period')
            ->firstOrFail();
        $data['course']->exam_at = '2022-01-01 00:00:00';
        $data['course']->exam_approved = 1;


        // $data['term'] = $data['course']->getTirm($data['site']->id);
        $data['questions_old'] = $data['course']->questions()->where(['status' => 1])
          ->whereRelation('translations', 'locale','=',app()->getlocale())
          ->orderBy('sequence','ASC')
          ->get();



        if($data['questions_old']->isEmpty()){
          $data['errors'] = [ __('trans.quiz_not_ready') ];
          return view('front.content.courses.quiz-errors', $data);
        }




        // default values
        $data['userCanOpenTest'] = true;
        $data['userHasTrays'] = false;
        $data['trays'] = 0;
        $data['quiz'] = [];





        $data['userGetXtraTray'] = false;
        $data['trays'] = maxTests(); // helpers/core
        $extraTraysService = new App\Services\ExtraTrays([
          'user' => Auth::user(),
          'site_id' =>  $data['site']->id,
          'term_id' => $data['course']->id,
          'locale' => app()->getlocale(),
        ]);
        // $data['userGetXtraTray'] = $extraTraysService->getUserXtraTrays()['userHasExtraTrays'];
        $data['trays'] = $extraTraysService->getUserEmailXtraTrays();
        $data['trays'] = $data['trays'] < maxTests() ? maxTests() : $data['trays'];


        $userTrays = Auth::guard('web')->user()->term_results->where('term_id', $data['course']->id )->count();
        $data['userTrays'] = $userTrays;
        $data['userHasTrays'] = $userTrays < $data['trays'];
        if(! $data['userHasTrays']){
          $data['errors'] = [ __('core.invalid_quiz_count') ];
          return view('front.content.courses.quiz-errors', $data );
        }
        /////////////////////////////



        // forQuizeTime
        $quizTimeService = new QuizTimeService([
            'user_id' => auth()->id(),
            'site_id' => $data['site']->id,
            'term_id' => $data['course']->id,
            'question_period' => $data['course']->question_period,
            'questions_count' => $data['questions_old']->count()
        ]);
        $data['quiz'] = $quizTimeService->startQuiz();


        if(! $data['quiz']['userHasRemainTime']){
            // user entered this quiz before and time finished
            // so get entered time to tell the user that he is entered this quiz at ...
            $data['enteredQuize'] = MemberQuizTime::where('user_id', Auth::id())->where('term_id', $data['course']->id)->select('start_time')->first();

            $quizTimeService->deleteQuizTime();

            $this->createFaildTermTestResult([
                  'no_test' => $userTrays + 1,
                  'user_id' => Auth::guard('web')->id(),
                  'site_id' => $data['site']->id,
                  'term_id' => $data['course']->id,
                  'term' => $data['course']->id
            ]);

            // save user course results
            // $this->saveFinalUserCourseResult(auth()->user(), $data['course']);

            // save user site results
            // $this->saveFinalUserSitesResults(auth()->user());
        }

        $data['current'] = 'term';
        return view('front.content.courses.quiz',$data);

    }

    public function storeTerm(Request $request)
    {

        $site_alias = \Route::input('site');
        $term_id = \Route::input('term');

        $validator = $this->validatorTerm(['site_alias' => $site_alias, 'term' => $term_id]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $site = App\site::where('status', 1)->whereTranslation('slug', $site_alias)->firstOrFail();
        $term = $site->terms()->where(['id' => $term_id, 'status' => 1])
            ->select('terms.id','title','exam_approved','exam_at','max_trys','sort','status','question_period')
            ->firstOrFail();


        // $trem = $course->getTirm($site->id);




        // 120 minutes
        $userCanOpenTest = true;
        // -------------------------------




        $userCanOpenTestInThisDate = true;








        $questions = $term->questions()->where(['status' => 1])->orderBy('sequence','ASC')->get();


        $user = $request->user();
        $no_test = $this->getUserTermTestsCount($term, app()->getlocale());



        $trays = maxTests(); // helpers/core
        $extraTraysService = new App\Services\ExtraTrays([
          'user' => Auth::user(),
          'site_id' =>  $site->id,
          'term_id' => $term->id,
          'locale' => app()->getlocale()
        ]);
        $trays = $extraTraysService->getUserEmailXtraTrays();
        $trays = $trays < maxTests() ? maxTests() : $trays;


        $data['userHasTrays'] = $no_test < $trays;
        if ($no_test >=  $trays){
            return back()->withErrors(['error' => __('core.invalid_quiz_count')]);
        }


        $rules = [] ;
        foreach ($questions as $row) {
            $rules['answers.'.$row->id] = [Rule::requiredIf($row->required)];
            switch ($row->type) {
                case 'true_false':
                    $rules['answers.'.$row->id][] = 'boolean';
                break;
                case 'range':
                    $rules['answers.'.$row->id][] = 'between:'.$row['options']['min'].','.$row['options']['max'] ;
                break;
                case 'drop_list':
                    $answer_ids = $row->answers()->where('status',1)->orderBy('sequence','ASC')->select('id')->pluck('id')->toArray();
                    if ($row->correct_answer > 1) {
                        $rules['answers.'.$row->id.'.*'] = [Rule::in($answer_ids)] ;
                    } else {
                        $rules['answers.'.$row->id][] = Rule::in($answer_ids) ;
                    }
                break;
                default:
                    $answer_ids = $row->answers()->where('status',1)->orderBy('sequence','ASC')->select('id')->pluck('id')->toArray();
                    $rules['answers.'.$row->id][] = Rule::in($answer_ids) ;
                break;
            }
        }

        $validator = Validator::make($request->all(),$rules);
        //  $request->v = 'v' . all answers must answerd
        //  $request->v = 'n' . dont validate all answers must answerd . came from timer may be user doesn't answered all questions_old buth the time is ended
        if($request->v === 'v'){
          if ($validator->fails()) {
              return redirect()->back()->withInput()->withErrors($validator);
          }
        }





        $tests = [] ;
        $correct_answers = 0;
        foreach ($questions as $key => $row){
            if (isset($request->answers[$row->id])) {
                if (count($row->correct_answer) > 1 && is_array($request->answers[$row->id])) {
                    $count = 0 ;
                    foreach ($row->correct_answer as $c) {
                        $count += in_array($c,$request->answers[$row->id]) ? 1 : 0 ;
                    }
                    $correct_answers += ( $count / count($row->correct_answer) ) * $row->degree ;
                } else {
                    $correct_answers += in_array($request->answers[$row->id],$row->correct_answer)  ? $row->degree : 0;
                }

                $qAnswers = is_array($request->answers[$row->id]) ? $request->answers[$row->id] : [$request->answers[$row->id]] ;
                // array_map('intval',$request->answers[$row->id]) : [intval($request->answers[$row->id])];
                foreach ($qAnswers as $qAnswer) {
                  $tests[] = [
                      // 'no_test' => intval($no_test + 1),
                      // 'user_id' => $user->id,
                      // 'course_id' => $course->id,
                      'question_id' => $row->id,
                      'answer_id' => intval($qAnswer),
                      'date' => date('Y-m-d'),
                  ];
               }
            }
        }


        $sumDegree = $questions->sum('degree');
        if ($sumDegree){
          $courseDegree = round( ( $correct_answers / $sumDegree ) * 100 , 2 );
        } else {
          $courseDegree = 0;
        }

        $degree = $courseDegree;
        // add zoom points --------------------------------------------
          // $extraPointsService = new \App\Services\ExtraPointsService(['user' => auth()->user(), 'course' => $course]);
          // $attandeDegree = $extraPointsService->calculateExtraPonits();
          $attandeDegree = 0;
          $degree = $degree + $attandeDegree;
          if($degree > 100) { $degree = 100;}
        // --------------------------------------------------------


        // $courseRate = $courseDegree >= 90 && $courseDegree <= 100 ? 5 : ($courseDegree >= 80 && $courseDegree < 90 ? 4 : ($courseDegree >= 70 && $courseDegree < 80 ? 3 : ($courseDegree >= 60 && $courseDegree < 70 ? 2 : ($courseDegree >= 50 && $courseDegree < 60 ? 1 : 0))));
        // $rate = $degree >= 90 && $degree <= 100 ? 5 : ($degree >= 80 && $degree < 90 ? 4 : ($degree >= 70 && $degree < 80 ? 3 : ($degree >= 60 && $degree < 70 ? 2 : ($degree >= 50 && $degree < 60 ? 1 : 0))));
        $courseRate = calculateCourseRate($courseDegree);
        $rate = calculateCourseRate($degree);



        // $no_test = $this->getUserTermTestsCount($term->id, app()->getlocale());
        // if ($no_test >=  $trays) {
        //     return back()->withErrors(['error' => __('core.invalid_quiz_count')]);
        // }



        $test_result = [
            'no_test' => intval($no_test + 1),
            'user_id' => $user->id,
            'site_id' => $site->id,
            // 'term_id' => $course->id,
            'term_id' => $term->id,
            // 'course_degree' => $courseDegree,
            'degree' => $degree,
            // 'course_rate' => $courseRate,
            'rate' => $rate,
            'locale' => app()->getLocale(),
            // 'flag' => 0,
            'status' => 1,
            // 'created_by' => 0,
        ];
        $termTestsResult = $term->term_results()->create($test_result);








        // forQuizeTime
        // delete quize time record of current test
        $quizTimeService = new QuizTimeService([
            'user_id' => auth()->id(),
            'site_id' => $site->id,
            'term_id' => $term->id,
            'question_period' => $term->question_period,
            'questions_count' => $questions->count()
        ]);
        $quizTimeService->deleteQuizTime();














        foreach ($tests as $key => $test) {
          $tests[$key]['term_test_result_id'] = $termTestsResult->id;
        }

        $term->tests()->createMany($tests);

        Session(['degree'=>$degree]);
        Session(['rate'=>$rate]);
        Session(['term_test_result_id'=>$termTestsResult->id]);
        Session(['no_test'=>$termTestsResult->no_test]);
        // if($attandeDegree){
        //   $request->session()->flash('attende', 'true');
        // }


        // randome code for every test
        $termTestsResult->code = $this->generateCourseCode();
        $termTestsResult->save();





        // save user terms results
        $this->saveFinalUserTermsResults(auth()->user(), $term);


        return redirect()->route('quiz_result',['site' => $site_alias,'course' => $term_id])
          ->with('success', __('core.valid_quiz_count',['degree' => $degree]));

    }







    private function saveFinalUserCourseResult($user, $course)
    {
        // save user course results
        try {
          $resultService = new \App\Services\ResultsService();
          $courseTestResults = $resultService->setUser($user)->setCourse($course)->saveFinalUserCourseResult();
        } catch (\Exception $e) {
            Log::channel('userresults')->info($e->getMessage());
        }
    }

    private function saveFinalUserTermsResults($user, $term)
    {
        // save user terms results
        try {
          $resultService = new \App\Services\ResultsService();
          $siteTestsResults = $resultService->setUser($user)->setTerm($term)->saveFinalUserTermsResults();
        } catch (\Exception $e) {
            Log::channel('userresults')->info($e->getMessage());
        }
    }

    private function saveFinalUserSitesResults($user)
    {
        // save user sites results
        try {
          $resultService = new \App\Services\ResultsService();
          $siteTestsResults = $resultService->setUser($user)->saveFinalUserSitesResults();
        } catch (\Exception $e) {
            Log::channel('userresults')->info($e->getMessage());
        }
    }



    private function generateCourseCode()
    {
        $testCodeExists = true;
        $testCode = '';
        do {
            $testCode = $this->globalService->generateRandomString(12);
            $testCodeExists = course_test_result::where('code',$testCode)->exists();
        } while ($testCodeExists == true );

        return $testCode;
    }





    public function userCanOpenTest($course_id, $user_id)
    {
        if ( Auth::id() == 5671 || Auth::id() == 5972 || Auth::id() == 5668 || Auth::id() == 12669 || Auth::id() == 43935 || Auth::id() == 44030 || Auth::id() == 59999 || Auth::id() == 214983){
          return true;
        }

        return $this->courseTestResultService->UserCanOpenTest( $course_id, Auth::guard('web')->id() );
    }

    public function result(Request $request)
    {
        $lang = App::getLocale() ;
        $data = $this->data($request,'home');

        return view('front.content.courses.quiz_result')->with('data',$data);
    }

    public function showAnswers(Request $request)
    {

        $lang = App::getLocale() ;
        $data = $this->data($request,'home');

        $courseTestResult = course_test_result::where('id', $request->id)
          ->where('user_id', Auth::id())
          ->where('no_test', '>=', 2)
          ->firstOrFail();

        $userTestResultAnswers = [];
        $globalService = new App\Services\GlobalService();
        $userTestResultAnswers = $globalService->getUserTestResultAnswers(['course_test_result_id'=> $courseTestResult->id]);

        return view('front.content.courses.quiz_answers')
          ->with('data',$data)
          ->with('courseTestResult',$courseTestResult)
          ->with('userTestResultAnswers',$userTestResultAnswers)
          ;

    }

    public function validatorIndex($params = [])
    {
        return Validator::make(['site_alias' => $params['site_alias'], 'course_alias' => $params['course_alias']], [
            'site_alias' => 'required|max:250|string',
            'course_alias' => 'required|max:250|string',
        ]);
    }

    public function validatorStore($params = [])
    {
        return Validator::make(['site_alias' => $params['site_alias'], 'course_id' => $params['course_id']], [
            'site_alias' => 'required|max:250|string',
            'course_id' => 'required|integer',
        ]);
    }

    public function validatorTerm($params = [])
    {
        return Validator::make(['site_alias' => $params['site_alias'], 'term_id' => $params['term']], [
            'site_alias' => 'required|max:250|string',
            'term_id' => 'required|integer',
        ]);
    }

    private function createFaildCourseTestResult($testData = [])
    {
        // ادخال اختبار راسب لان الطالب تعدى الوقت المحدد
        $test_result = [
            'no_test' => $testData['no_test'],
            'user_id' => $testData['user_id'],
            'site_id' => $testData['site_id'],
            'course_id' => $testData['course_id'],
            'term_id' => $testData['term'],
            'course_degree' => 0,
            'degree' => 0,
            'course_rate' => 0,
            'rate' => 0,
            'locale' => app()->getLocale(),
            'flag' => 0,
            'status' => 1,
            'created_by' => 0,
        ];
        $coursTestsResult = course_test_result::create($test_result);

        // randome code for every test
        $coursTestsResult->code = $this->generateCourseCode();
        $coursTestsResult->save();

    }


    private function createFaildTermTestResult($testData = [])
    {
        // ادخال اختبار راسب لان الطالب تعدى الوقت المحدد
        $test_result = [
            'no_test' => $testData['no_test'],
            'user_id' => $testData['user_id'],
            'site_id' => $testData['site_id'],
            'course_id' => $testData['course_id'],
            'term_id' => $testData['term_id'],
            'course_degree' => 0,
            'degree' => 0,
            'course_rate' => 0,
            'rate' => 0,
            'locale' => app()->getLocale(),
            'flag' => 0,
            'status' => 1,
            'created_by' => 0,
        ];
        $coursTestsResult = course_test_result::create($test_result);

        // randome code for every test
        $coursTestsResult->code = $this->generateCourseCode();
        $coursTestsResult->save();

    }


    private function getUserCourseTestsCount($course_id, $locale = null)
    {
      return Auth::guard('web')->user()->courseTestsCount($course_id, $locale);
    }

    private function getUserTermTestsCount($term, $locale = null)
    {
      return (new \App\Services\TermTestResultService())->getUserTestsCountOfTerm($term, Auth::user(), app()->getLocale());

    }

}
