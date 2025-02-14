<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionAnswerTranslation;
use App\Models\Test;
use App\Services\TestService;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\site;
use App\course;
use App\Term;
use App\course_question;
use App\course_answer;
use App\language;
use App\libraries\_commonfn;
use App\Imports\Questions as QuestionsImport;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\libraries\Helpers;
use App\Exports\CourseQustionsExport;
use App\Traits\CacheTrait;
use App\Services\LanguageService;
use App\Services\CourseService;
use App\Services\TermService;
use App\Services\QuestionService;
use Illuminate\Validation\ValidationException;


class QuestionController extends Controller
{
    use CacheTrait;
    private $questions_for;

    public function __construct(
        private LanguageService $languageService,
        private CourseService $courseService,
        private TermService $termService,
        private QuestionService $questionService,
        private TestService $testService,
        Request $request
      )
    {
        $this->questions_for = $request->query('type');
    }


    /*** Get all questions of test ***/
    public function index(Request $request)
    {
        ini_set('memory_limit', '512M');

        $language = $this->getLanguage();
        $test = Test::findOrFail($request->test);

        $languages = $this->languageService->getAll();
        $questions = $this->testService->loadTestQuestionsAnswersOfLanguage($test, $language);
        $questionsTypes = Question::types();

        return view ('back.content.questions.index', compact(['test','languages','questions','questionsTypes']));
    }


    /*** Import questions ***/
    public function import(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'import_file' => ['required','file'], // 'mimes:csv,xls,xlsx,txt,html'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $test = Test::findOrFail($request->test);

        Excel::import(new QuestionsImport($test), $request->file('import_file'));

        return redirect()->back()->with('success', 'Question Imported Successfully!');

    }


    public function edit(Request $request)
    {
        // ini_set('memory_limit', '512M');

        // display all questions
        $data['site_id'] = $request->site;
        $data['course_id'] = $request->course;
        $language = $this->getLanguage();
        $data['site'] = site::where('id',$data['site_id'])->firstOrFail();

        if($this->questions_for == 'term'){
            $data['course'] = $data['site']->terms()->findOrFail($data['course_id']);
        } else {
            $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);
        }

        $data['languages'] = $this->languageService->getAll();

        ini_set('memory_limit', '512M');
        if($this->questions_for == 'term'){
          $data['questions'] = $this->termService->getTermQuestionsAnswersByLanguage($data['course'], $language);
        } else {
          $data['questions'] = $this->courseService->getCourseQuestionsAnswersByLanguage($data['course'], $language);
        }


        return view ('back.content.questions.index', $data);
    }

//    public function import(Request $request)
//    {
//
//        $site_id = \Route::input('site');
//        $course_id = \Route::input('course');
//        $validator = Validator::make($request->all(), [
//            'import_file' => ['required','file'],
//            // 'import_file' => ['required','file','mimes:csv,xls,xlsx,txt,html'],
//        ]);
//
//        if ($validator->fails()) {
//            return redirect()->back()->withInput()->withErrors($validator);
//        }
//
//        Excel::import(new QuestionsImport($site_id, $course_id, $this->questions_for), $request->file('import_file'));
//
//        return redirect()->back()->with('success', 'Question Imported Successfully!');
//
//        // return redirect()->route('dashboard.test_results.index',['site' => $site_id,'course' => $course_id])
//        //   ->with('success', 'Members Results Added Successfully!');
//
//    }

    public function store(Request $request, Test $test)
    {
        $language = $request->language;

        $validator = Validator::make($request->all(), [
            'questions.*.name.*' => 'max:500',
            'questions.*.name.'.config('app.fallback_locale') => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }




        $questionData = $request->question[0];


        // check if correctAnswer exists only if not essay
        if (! $this->questionService->ignoreAnswers($questionData['type'])) {
            $validateCorrectAnswer = $this->getCorrectAnswerFromRequest($questionData);
            if (! $validateCorrectAnswer){
//                return response()->json(['msg' => 'Question updating Error No Correct Answer!', 'alert' => 'swal']);
                return redirect()->back()->with('fail', 'Question updating Error!! No Correct Answer!');
                // throw ValidationException::withMessages(['general' => 'Question updating Error No Correct Answer!' ]);
            }
        }


        $maxSequence = $this->getMaxQuestionSequence($test);
        $maxSequence = $maxSequence ? $maxSequence+1 : 0;



//        dd($questionData['type']);


        DB::beginTransaction();
        try {

            // store question
            $question = new Question();
            $question->test_id = $test->id;
            // $question->name = $request->question_name; // error because the package will create translation one with default langauge
            $question->type = $questionData['type'];
            $question->degree = $questionData['degree'];
            $question->duration = $questionData['duration'];
            $question->status = intval(@$questionData['status']);
            $question->required = intval(@$questionData['required']);
            $question->sequence = $maxSequence;
            $question->options = [];
            if (Auth::guard('admin')->check()){
                $question->created_by_admin_id = Auth::guard('admin')->id();
            }
            if (Auth::guard('teacher')->check()){
                $question->created_by_teacher_id = Auth::guard('teacher')->id();
            }
            foreach ($questionData['translations'] as $language => $translation)
            {
                if ($translation['name']){
                    $questionTranslation = $question->translateOrnew($language);
                    $questionTranslation->title = $translation['name'];
                }
            }
            $question->save();

            // save answers only if not essay
            if (! $this->questionService->ignoreAnswers($questionData['type'])) {
                $correctAnswers = null;
                // new answers
                $sequence = 0;
                foreach (@$questionData['new_answers'] ?? [] as $id => $answerData) {

                    $sequence = $sequence + 1 ;
                    $questionAnswer = QuestionAnswer::create([
                        'question_id' => $question->id,
                        'status' => intval(@$answerData['status']),
                        'sequence' => $sequence
                    ]);

                    if (isset($answerData['is_correct'])){
                        $correctAnswers[] = $questionAnswer->id;
                    }

                    // create answer translations
                    foreach(@$answerData['translations'] ?? [] as $locale => $translation){
                        if ($translation['name']){
                            $questionAnswer->translations()->create([
                                'locale' => $locale,
                                'title' => $translation['name']
                            ]);
                        } else {
                            $questionAnswer->translations()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
                        }
                    }
                }

                $question->update(['correct_answers' => $correctAnswers]);
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
        } catch (\Error $e) {
            DB::rollback();
            return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
        }

//        return response()->json([
//            'msg' => 'Successfully',
//            'alert' => 'swal',
//            'redir' => true
//        ]);

        return redirect()->back()->with('success', 'Question Added Successfully!');
    }




    public function storeQuestion(Request $request)
    {

        $language = $request->language;

        $site_id = \Route::input('site');
        $course_id = \Route::input('course');

        $validator = Validator::make($request->all(), [
            'questions.*.name.*' => 'max:500',
            'questions.*.name.'.config('app.fallback_locale') => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $site = site::where('id', $site_id)->firstOrFail();
        if($this->questions_for == 'term'){
          $course = $site->terms()->findOrFail($course_id);
        } else {
          $course = $site->courses()->findOrFail($course_id);
        }


        $questionData = $request->question[0];


        $validateCorrectAnswer = $this->getCorrectAnswerFromRequest($questionData);
        if (! $validateCorrectAnswer){
          return response()->json(['msg' => 'Question updating Error No Correct Answer!', 'alert' => 'swal']);
          // throw ValidationException::withMessages(['general' => 'Question updating Error No Correct Answer!' ]);
        }


        $maxSequence = $this->getMaxQuestionSequence($course);
        $maxSequence = $maxSequence ? $maxSequence+1 : 0;





        DB::beginTransaction();
        try {

            // store question
            $question = new course_question();
            if($this->questions_for == 'term'){
              $question->term_id = $course_id;
            } else {
              $question->course_id = $course_id;
            }
            // $question->name = $request->question_name; // error because the package will create translation one with default langauge
            $question->type = $questionData['type'];
            $question->degree = $questionData['degree'];
            $question->status = intval(@$questionData['status']);
            $question->required = intval(@$questionData['required']);
            $question->sequence = $maxSequence;
            $question->options = [];
            $question->updated_by = Auth::guard('admin')->user()->id;
            foreach ($questionData['translations'] as $language => $translation)
            {
                if ($translation['name']){
                  $questionTranslation = $question->translateOrnew($language);
                  $questionTranslation->name = $translation['name'];
                }
            }
            $question->save();


            $correctAnswer = null;
            // new answers
            $sequence = 0;
            foreach (@$questionData['new_answers'] ?? [] as $id => $answerData) {

                $sequence = $sequence + 1 ;
                $questionAnswer = course_answer::create([
                  'question_id' => $question->id,
                  'status' => intval(@$answerData['status']),
                  'sequence' => $sequence
                ]);

                if (! $correctAnswer){
                  $correctAnswer = isset($answerData['is_correct']) ? $questionAnswer->id : null;
                }

                  // create answer translations
                  foreach(@$answerData['translations'] ?? [] as $locale => $translation){
                    if ($translation['name']){
                        $questionAnswer->translations()->create([
                            'locale' => $locale,
                            'name' => $translation['name']
                        ]);
                    } else {
                        $questionAnswer->translations()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
                    }
                  }
            }



            $correctAnswer = [ 'a' => $correctAnswer];
            $question->update(['correct_answer' => $correctAnswer]);


            DB::commit();
          } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
          } catch (\Error $e) {
            DB::rollback();
            return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
          }

          return response()->json([
            'msg' => 'Successfully',
            'alert' => 'swal',
            'redir' => true
          ]);

          return redirect()->back()->with('success', 'Question Added Successfully!');


    }

//    public function update(Request $request)
//    {
//        dd($request->question);
//      $language = $request->language;
//
//      $validator = Validator::make($request->all(), [
//          'questions_old.*.name.*' => 'max:500',
//          'questions_old.*.name.'.config('app.fallback_locale') => 'required',
//      ]);
//
//      if ($validator->fails()) {
//        return response()->json([
//          'msg' => 'Error',
//          'alert' => 'swal',
//        ]);
//        // return redirect()->back()->withInput()->withErrors($validator);
//      }
//
//
//
//
//
//      $question = course_question::findOrFail($request->id);
//      $questionData = $request->question[$request->id];
//
//      $validateCorrectAnswer = $this->getCorrectAnswerFromRequest($questionData);
//      if (! $validateCorrectAnswer){
//        return response()->json(['msg' => 'Question updating Error No Correct Answer!', 'alert' => 'swal']);
//        // throw ValidationException::withMessages(['general' => 'Question updating Error No Correct Answer!' ]);
//      }
//
//
//      DB::beginTransaction();
//      try {
//
//          // update question
//          $question->update([
//              'type' => $request->type ,
//              'status' => intval(@$questionData['status']),
//              'required' => intval(@$questionData['required']),
//              'degree' => $questionData['degree'],
//          ]);
//
//          // update question translations
//          foreach(@$questionData['transaltions'] ?? [] as $locale => $translation){
//              if ($translation['name']){
//                  $question->translation()->updateorcreate([
//                      'locale' => $locale
//                  ],[
//                      'name' => $translation['name']
//                  ]);
//              } else {
//                $question->translation()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
//              }
//          }
//
//
//
//          $correctAnswer = null;
//          // update answers
//          $sequence = 0;
//          foreach ($questionData['answers'] as $id => $answerData) {
//
//              if (! $correctAnswer){
//                $correctAnswer = isset($answerData['is_correct']) ? $id : null;
//              }
//
//              $sequence = $sequence + 1 ;
//              $questionAnswer = course_answer::updateorcreate([
//                  'id' => $id,
//              ],[
//                'question_id' => $question->id,
//                'status' => intval(@$answerData['status']),
//                'sequence' => $sequence
//              ]);
//
//                // update answer translations
//                foreach(@$answerData['translations'] ?? [] as $locale => $translation){
//                  if ($translation['name']){
//                      $questionAnswer->translation()->updateorcreate([
//                          'locale' => $locale
//                      ],[
//                          'name' => $translation['name']
//                      ]);
//                  } else {
//                    $questionAnswer->translation()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
//                  }
//                }
//          }
//
//
//          // delete answers that user delete it
//          $question->answers()->whereNotIn('id', array_keys($questionData['answers']))->forcedelete();
//
//
//
//          // if user add new answers insert them
//          foreach (@$questionData['new_answers'] ?? [] as $id => $answerData) {
//
//              $sequence = $sequence + 1 ;
//              $questionAnswer = course_answer::create([
//                'question_id' => $question->id,
//                'status' => intval(@$answerData['status']),
//                'sequence' => $sequence
//              ]);
//
//              if (! $correctAnswer){
//                $correctAnswer = isset($answerData['is_correct']) ? $questionAnswer->id : null;
//              }
//
//                // create answer translations
//                foreach(@$answerData['translations'] ?? [] as $locale => $translation){
//                  if ($translation['name']){
//                      $questionAnswer->translation()->create([
//                          'locale' => $locale,
//                          'name' => $translation['name']
//                      ]);
//                  }
//                  // else {
//                  //      $questionAnswer->translation()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
//                  // }
//                }
//          }
//
//          $correctAnswer = [ 'a' => $correctAnswer];
//          $question->update(['correct_answer' => $correctAnswer]);
//
//        DB::commit();
//      } catch (\Exception $e) {
//        DB::rollback();
//        return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
//      } catch (\Error $e) {
//        DB::rollback();
//        return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
//      }
//
//
//
//      $question = $this->getQuestionWithAnswersByLanguage($question);
//      return $this->renderQuestion($question);
//
//
//      return redirect()->back()->with('success', 'Question updated Successfully!');
//
//
//    }

    public function update(Request $request, Question $question)
    {
        $language = $request->language;

        $validator = Validator::make($request->all(), [
            'questions.*.name.*' => 'max:500',
            'questions.*.name.'.config('app.fallback_locale') => 'required',
        ]);

        if ($validator->fails()) {
//            return response()->json([
//                'msg' => 'Error',
//                'alert' => 'swal',
//            ]);
             return redirect()->back()->withInput()->withErrors($validator);
        }


        // $question = $request->question;
        $questionData = $request->question[key($request->question)];


        // check if correctAnswer exists only if not essay
        if (! $this->questionService->ignoreAnswers($questionData['type'])) {
            $validateCorrectAnswer = $this->getCorrectAnswerFromRequest($questionData);
            if (! $validateCorrectAnswer){
//                return response()->json(['msg' => 'Question updating Error No Correct Answer!', 'alert' => 'swal']);
                return redirect()->back()->with('fail', 'Question updating Error!! No Correct Answer!');
                // throw ValidationException::withMessages(['general' => 'Question updating Error No Correct Answer!' ]);
            }
        }


        if (isset($request->question[$question->id]['status']) && in_array($request->question[$question->id]['status'], $request->question[$question->id])) {
            $question_status = $request->question[$question->id]['status'];
        } else {
            $question_status = 0;
        }

        if (isset($request->question[$question->id]['required']) && in_array($request->question[$question->id]['required'], $request->question[$question->id])) {
            $question_required = $request->question[$question->id]['required'];
        } else {
            $question_required = 0;
        }

//        return $question_required . $question_status;


        DB::beginTransaction();
        try {

            // update question
            $question->update([
                'type' => $questionData['type'],
//                'status' => intval(@$questionData['status']),
//                'required' => intval(@$questionData['required']),
                'status' => $question_status,
                'required' => $question_required,
                'duration' => $questionData['duration'],
                'degree' => $questionData['degree'],
            ]);





            // update question translations
            foreach(@$questionData['transaltions'] ?? [] as $locale => $translation){
                if ($translation['name']){
                    $question->translations()->updateorcreate([
                        'locale' => $locale
                    ],[
                        'title' => $translation['name']
                    ]);
                } else {
                    $question->translations()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
                }
            }


            // save answers only if not essay
            if (! $this->questionService->ignoreAnswers($questionData['type'])) {
                $correctAnswers = null;
                // update answers
                $sequence = 0;

                if (isset($questionData['answers'])) {
                    foreach ($questionData['answers'] as $id => $answerData) {

                        if (isset($answerData['is_correct'])){
                            $correctAnswers[] = $id;
                        }


                        $sequence = $sequence + 1 ;
                        $questionAnswer = QuestionAnswer::updateorcreate([
                            'id' => $id,
                        ],[
                            'question_id' => $question->id,
                            'status' => intval(@$answerData['status']),
                            'sequence' => $sequence
                        ]);

                        // update answer translations
                        foreach(@$answerData['translations'] ?? [] as $locale => $translation){
                            if ($translation['name']){
                                $questionAnswer->translations()->updateorcreate([
                                    'locale' => $locale
                                ],[
                                    'title' => $translation['name']
                                ]);
                            } else {
                                $questionAnswer->translations()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
                            }
                        }
                    }


                    // delete answers that user delete it
                    $question->answers()->whereNotIn('id', array_keys($questionData['answers']))->forcedelete();
                }





                // if user add new answers insert them
                foreach (@$questionData['new_answers'] ?? [] as $id => $answerData) {

                    $sequence = $sequence + 1 ;
                    $questionAnswer = QuestionAnswer::create([
                        'question_id' => $question->id,
                        'status' => intval(@$answerData['status']),
                        'sequence' => $sequence
                    ]);


                    if (isset($answerData['is_correct'])){
                        $correctAnswers[] = $questionAnswer->id;
                    }

                    // create answer translations
                    foreach(@$answerData['translations'] ?? [] as $locale => $translation){
                        if ($translation['name']){
                            $questionAnswer->translations()->create([
                                'locale' => $locale,
                                'title' => $translation['name']
                            ]);
                        }
                        // else {
                        //      $questionAnswer->translation()->where('locale', $locale)->delete(); // mybe there is a record before, and the user want to clear it's data
                        // }
                    }
                }


                $question->update(['correct_answers' => $correctAnswers]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
//            return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
            return redirect()->back()->with('fail', $e->getMessage());
        } catch (\Error $e) {
            DB::rollback();
//            return response()->json(['msg' => $e->getMessage(), 'alert' => 'swal']);
            return redirect()->back()->with('fail', $e->getMessage());
        }



        $question = $this->loadQuestionWithAnswersByLanguage($question);
//        return $this->renderQuestion($question);


        return redirect()->back()->with('success', 'Question updated Successfully!');


    }

    public function delete(Request $request)
    {

        if ($request->id){
            course_question::where('id', $request->id)->forceDelete();

            return response()->json([
              'msg' => 'Successfully',
              'alert' => 'swal',
              'remove_div_id' => $request->id
            ]);
        }


        if ($request->course){
            if($this->questions_for == 'term'){
              term::where('id', $request->course)->firstorfail()->questions()->forceDelete();
            } else {
              course::where('id', $request->course)->firstorfail()->questions()->forceDelete();
            }
        }

        return redirect()->back()->with('success', 'Question deleted Successfully!');
    }


    public function destroy(Request $request, ?Test $test, ?Question $question)
    {
//        dd($request);
        $question?->forceDelete();

        return redirect()->back()->with('success', 'Question deleted Successfully!');
//        return response()->json([
//            'msg' => 'Successfully',
//            'alert' => 'swal',
//            'remove_div_id' => $question->id
//        ]);

    }

    public function destroyAll(Request $request, Test $test)
    {
        $test?->questions()->forceDelete();
        return redirect()->back()->with('success', 'Questions deleted Successfully!');
    }

    public function deleteAnswer(Request $request, $questionAnswer)
    {
        $questionAnswer = QuestionAnswer::find($questionAnswer);

        if ($questionAnswer){
            $questionAnswerTranslation = QuestionAnswerTranslation::query()
                ->where('question_answer_id',$questionAnswer->id)->get();

            if ($questionAnswerTranslation)
            {
                $questionAnswerTranslation->each->forceDelete();
            }

            $questionAnswer->forceDelete();
        }



        return redirect()->back()->with('success', 'Answer Deleted Successfully!');
//        return response()->json([
//            'msg' => 'Successfully',
//            'alert' => 'swal',
//            'remove_div_id' => $questionAnswer->id
//        ]);

    }

    private function loadQuestionWithAnswersByLanguage($question)
    {
        return $this->questionService->loadQuestionWithAnswersByLanguage($question);
    }

    private function getCorrectAnswerFromRequest($questionData)
    {

        $correctAnswer = null;
        foreach ($questionData['answers'] ?? [] as $answer) {
          if( isset($answer['is_correct']) ){
            $correctAnswer = [ 'a' => $answer['is_correct']];
          }
        }

        if (! $correctAnswer){
          foreach ($questionData['new_answers'] ?? [] as $answer) {
            if( isset($answer['is_correct']) ){
              $correctAnswer = [ 'a' => $answer['is_correct']];
            }
          }
        }

        return $correctAnswer;
    }

    private function getQuestionWithAnswersByLanguage($question)
    {
        return $this->questionService->getQuestionWithAnswersByLanguage($question);
    }

    private function renderQuestion($question)
    {
        return response()->json([
          'msg' => 'Successfully',
          'alert' => 'swal',
          'hide_model' => 'true',
          'html' => view('back.content.questions.question', ['mode' => 'edit','question' => $question])->render()
        ]);
    }

    public function getMaxQuestionSequence($course)
    {
        return $course->questions()->max('sequence');
    }

    public function export(Request $request)
    {
        dd($request->query());

        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        if($this->questions_for == 'term'){
          $course = term::findOrFail($course_id);
        } else {
          $course = course::findOrFail($course_id);
        }

        $type ='xlsx';

        $export = new CourseQustionsExport($course_id);
        $extensions = config('excel.extension_detector');

        if (in_array($type,array_keys($extensions))) {
            return $export->download($course->name.'.'.$type,$extensions[$type]);
        } else {
            return $export->download($course->name.'.csv', \Maatwebsite\Excel\Excel::XLSX);
        }

    }

    private function getLanguage()
    {
      return request()->query('language') ?? null;
    }

}
