<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\site;
use App\course;
use App\course_question;
use App\course_answer;
use App\language;
use App\libraries\_commonfn;
use App\Imports\Questions as QuestionsImport;
use Illuminate\Support\Facades\Input;
use Validator;
use Excel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\libraries\Helpers;
use Illuminate\Support\Facades\Cache;
use App\Exports\CourseQustionsExport;


class questionsOrg extends Controller
{
    public function edit(Request $request)
    {


        // display all questions_old
        $data['site_id'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $id = \Route::input('question');
        $data['site'] = site::where('id',$data['site_id'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);

        if (!Cache::has('languages-'.$data['site_id'])) {
            Cache::forever('languages-'.$data['site_id'] , language::where('status',1)->pluck('name','alies')->toArray());
        }

        $data['languages'] = [];
        foreach (Cache::get('languages-'.$data['site_id']) as $alias => $name) {
            if (in_array($alias,$data['course']->languages)) {
                $data['languages'][$alias] = $name;
            }
        }

        $questions = [] ;
        $pro_res = $data['course']->questions()->orderBy('sequence', 'ASC')->get();


        foreach($pro_res as $row) {
            foreach ($row->getTranslationsArray() as $lang => $values) {
                foreach ($values as $key => $value) {
                    $row[$key] = is_array($row[$key]) ? array_merge($row[$key] , [$lang => $value]) : [$lang => $value] ;
                }
            }

            $questions[$row['sequence']] = $row->toArray();
            // $answer['correct_answer'] = in_array($answer->id,$row['correct_answer']) ? 1 : 0 ;
            $questions[$row['sequence']]['answers'] = [];

            if ($row->type == 'true_false') {
                $questions[$row['sequence']]['correct_answer'][0] = $row['correct_answer'][0] == '1' ? true : null ;
            }

            foreach($row->answers as $answer){
                foreach ($answer->getTranslationsArray() as $lang => $values){
                    foreach ($values as $key => $value) {
                        $answer[$key] = is_array($answer[$key]) ? array_merge($answer[$key] , [$lang => $value]) : [$lang => $value] ;
                    }
                }
                $answer['correct_answer'] = in_array($answer->id,$row['correct_answer']) ? 1 : 0 ;
                $questions[$row['sequence']]['answers'][$answer['sequence']] = $answer->toArray();
            }
        }

        $data['script'] = view('back.content.courses.questions_old.script',['questions_old' => json_encode(@$questions),'languages' => $data['languages']]);
        return view ('back.content.courses.questions_old.index',$data);
    }

    public function update(Request $request)
    {

      $site_id = \Route::input('site');
      $course_id = \Route::input('course');
      $id = \Route::input('question');
      $validator = Validator::make($request->all(), [
          'questions_old.*.name.*' => 'max:500',
          'questions_old.*.name.'.config('app.fallback_locale') => 'required',
      ]);

      if ($validator->fails()) {
          return redirect()->back()->withInput()->withErrors($validator);
      }

      $site = site::where('id',$site_id)->firstOrFail();
      $course = $site->courses()->findOrFail($course_id);

      // 01
      if ($request->has('deleteThisOnly')){

            $getOrder = explode('_', $request->deleteThisOnly);

            // if no order
            if (! isset($getOrder[1])){
              return redirect()->route('dashboard.courses.questions_old.edit',['site' => $site_id,'course' => $course_id])->withErrors(['msg' => 'Question deleteing Error!']);
            }

            $questionId = $request->questions[ $getOrder[1] ]['id'];
            $question = course_question::findOrFail($questionId)->forceDelete();

            return redirect()->route('dashboard.courses.questions_old.edit',['site' => $site_id,'course' => $course_id])->with('success', 'Question deleted Successfully!');
       }



      // 02
      // update this qustion and it's answers only
      // dont aupdate the whole page
      if ($request->has('updateThisOnly')){

            $getOrder = explode('_', $request->updateThisOnly);

            // if no order
            if (! isset($getOrder[1])){
              return redirect()->route('dashboard.courses.questions_old.edit',['site' => $site_id,'course' => $course_id])->withErrors(['msg' => 'Question updating Error!']);
            }


            $questionData = $request->questions[ $getOrder[1] ];
            $questionId = $request->questions[ $getOrder[1] ]['id'];
            $question = course_question::findOrFail($questionId);


            // correct answer -----------------------------------
            $correctAnswer = 0;
            foreach ($questionData['answers'] as $answer) {
              if( isset($answer['is_correct']) ){
                $correctAnswer = [ 'a'. $answer['is_correct'] => $answer['id']];
              }
            }
            if (! $correctAnswer){
              return redirect()->route('dashboard.courses.questions_old.edit', ['site' => $site_id,'course' => $course_id])->withErrors(['msg' => 'Question updating Error No Correct Answer!']);
            }
            // --------------------------------------------------


            $question->update([
                'type' => $questionData['type'] ,
                'status' => $questionData['status'] ?? false,
                'required' => $questionData['required'] ?? false,
                'degree' => $questionData['degree'],
                'correct_answer' => $correctAnswer,
            ]);


            foreach($questionData['name'] as $key => $value){
                $translate = $question->translate($key);
                $translate->name = $value;
                $translate->save();
            }


            foreach ($questionData['answers'] as $answer) {
                $getAnswer = course_answer::findOrFail($answer['id']);
                $getAnswer->status = $answer['status'] ?? 0;
                $getAnswer->save();

                foreach ($answer['name'] as $key => $trans) {
                  $translate = $getAnswer->translate($key);
                  $translate->name = $trans;
                  $translate->save();
                }
            }

            return redirect()->route('dashboard.courses.questions_old.edit',['site' => $site_id,'course' => $course_id])->with('success', 'Question updated Successfully!');

      }




      // 03
      // update all qustions, all page
      $this->sync_answers($request,$course,$course->languages);

      return redirect()->route('dashboard.courses.questions_old.edit',['site' => $site_alias,'course' => $course_id])->with('success', 'Question updated Successfully!');

    }

    public function import(Request $request)
    {

        $site_id = \Route::input('site');
        $course_id = \Route::input('course');
        $validator = Validator::make($request->all(), [
            'import_file' => ['required','file'],
            // 'import_file' => ['required','file','mimes:csv,xls,xlsx,txt,html'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Excel::import(new QuestionsImport($site_id,$course_id), $request->file('import_file'));
        return redirect()->route('dashboard.test_results.index',['site' => $site_id,'course' => $course_id])->with('success', 'Members Results Added Successfully!');

    }

    public function sync_answers(Request $request,$save,$languages)
    {

        $fields = $request->get('questions_old', []);
        // dd($fields);

        if(empty($fields))
        {
            $all_fields = (new _commonfn)->get_question($save->id,'id');
            if(!empty($all_fields)) {
                foreach (array_keys($all_fields) as $all_element_id)
                {
                    (new _commonfn)->delete_question($all_element_id);
                }
            }
        }
        else
        {
            (new _commonfn)->update_questions($save->id,$languages,$fields);
        }
    }

    public function delete(Request $request)
    {
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);
        $questions = $course->questions()->forceDelete();

        return redirect()->back()->with('success', 'Question deleted Successfully!');
    }

    public function createQuestion(Request $request)
    {

          $data['site_alias'] = \Route::input('site');
          $data['course_id'] = \Route::input('course');
          $id = \Route::input('question');
          $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
          $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);

          $data['languages'] = [];
          foreach (Cache::get('languages-'.$data['site_alias']) as $alias => $name) {
              if (in_array($alias,$data['course']->languages)) {
                  $data['languages'][$alias] = $name;
              }
          }

          $maxSequence = $this->getMaxQuestionSequence($data['course']);
          $maxSequence = $maxSequence ? $maxSequence+1 : 0;

          $questions = [
              1 =>[
                'id' => '',
                'course_id' => $data['course']->id,
                'type' => 'drop_list',
                'degree' => '',
                'correct_answer' => [],
                'required' => '',
                'options' => [],
                'sequence' => $maxSequence,
                'status' => 1,
                'name' => ['ar' => ''],
                'translations' => [],
                'answers' => [
                  1 => [
                    'id' => '1',
                    'question_id' => '',
                    'sequence' => '',
                    'status' => '',
                    'correct_answer' => '',
                    'name' => [],
                    'translations' => [],
                  ]
                ]
              ]
          ];
          // dd($questions_old);

          $data['script'] = view('back.content.courses.questions_old.script',['questions_old' => json_encode(@$questions),'languages' => $data['languages'], 'hide_update' => true]);
          return view('back.content.courses.questions_old.create',$data);

    }

    public function storeQuestion(Request $request)
    {


        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $id = \Route::input('question');
        $validator = Validator::make($request->all(), [
            'questions_old.*.name.*' => 'max:500',
            'questions_old.*.name.'.config('app.fallback_locale') => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $site = site::where('alias',$site_alias)->firstOrFail();
        $course = $site->courses()->findOrFail($course_id);



        $question = current( $request->questions ); // first element of array

        $languages = array_keys($question['name']);

        $maxSequence = $this->getMaxQuestionSequence($course);
        $maxSequence = $maxSequence ? $maxSequence+1 : 0;

        DB::beginTransaction();
        try {

            // store question
            $courseQuestion = new \App\course_question();
            $courseQuestion->updated_by = Auth::guard('admin')->user()->id;
            $courseQuestion->course_id = $course_id;
            $courseQuestion->type = $question['type'];
            foreach ($languages as $lang)
            {
                $save_tr = $courseQuestion->translateOrnew($lang);
                $save_tr->name = $question['name'][$lang];
            }
            $courseQuestion->degree = $question['degree'];
            $courseQuestion->status = $question['status'];
            $courseQuestion->required = $question['required'] ?? 0;
            $courseQuestion->sequence = $question['sequence'] ?? $maxSequence;
            $courseQuestion->options = [];
            $courseQuestion->correct_answer = [];
            $courseQuestion->save();



            // store answers
            $sequence = 1;
            $correctAnswers = [];
            foreach ($question['answers'] as $key => $answer) {
                    $courseAnswer = new \App\course_answer();
                    $courseAnswer->question_id = $courseQuestion->id;
                    $courseAnswer->status = intval(@$answer['status']);
                    $courseAnswer->sequence = $sequence;

                    foreach ($languages as $lang)
                    {
                        $answers_tr = $courseAnswer->translateOrnew($lang);
                        $answers_tr->name = strval(@$answer['name'][$lang]);
                    }

                    $sequence++;

                    $courseAnswer->save();

                    if ( isset($answer['is_correct']) ) {
                        $correctAnswers = array_merge( $correctAnswers, ['a'.$key => $courseAnswer->id] ) ;
                    }

                }



            // update question correct answer
            $courseQuestion->correct_answer = $correctAnswers;
            $courseQuestion->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }


        return redirect()->back()->with('success', 'Question Added Successfully!');


    }

    public function getMaxQuestionSequence($course)
    {
        return $course->questions()->max('sequence');
    }

    public function export(Request $request)
    {

        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');
        $course = course::findOrFail($course_id);

        $type ='xlsx';

        $export = new CourseQustionsExport($course_id);
        $extensions = config('excel.extension_detector');

        if (in_array($type,array_keys($extensions))) {
            return $export->download($course->name.'.'.$type,$extensions[$type]);
        } else {
            return $export->download($course->name.'.csv', \Maatwebsite\Excel\Excel::XLSX);
        }

    }

}
