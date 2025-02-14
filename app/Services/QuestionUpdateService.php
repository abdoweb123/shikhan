<?php

namespace App\Services;

use App\Models\Test;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Auth;

class QuestionUpdateService
{

  public function updateQuestions(Test $test, $languages, $data)
  {

      $sequence = 1;

      $all_questions = $this->get_question($test->id, 'id');
      // if question dosent have questions in course_questions then $all_questions will be empty


      foreach ($data as $row)
      {
          if (!empty($row['id'])){
              if (in_array($row['id'],array_keys($all_questions))) {
                  unset($all_questions[$row['id']]);
              }
          }

          if (isset($row['options']))
          {
              $seq_op = 1;
              $options = $row['options'];$row['options'] = [];
              foreach ($options as $k => $op)
              {
                  if($row['type'] != 'range')$op['status'] = intval(@$op['status']);
                  $row['options'][($row['type'] == 'range')?$k:$seq_op] = $op;
                  $seq_op++;
              }
          }

          $row['test_id'] = $test->id;
          $row['degree'] = intval(@$row['degree']);
          $row['status'] = intval(@$row['status']);
          $row['required'] = intval(@$row['required']);
          $row['sequence'] = $sequence;
          $this->save_question($row, $languages);
          $sequence++;
      }

      foreach (array_keys($all_questions) as $all_element_id){
          $this->delete_question($all_element_id);
      }

  }

  public function get_question($testId, $order_by = 'sequence')
  {

      $db_question = $this->query_question(['test_id'=>$testId]);

      $result = $db_question->orderBy('sequence', 'ASC')->get()->toArray();

      $return = [];

      foreach($result as $row)
      {
          if (is_numeric($row['type'])){
              $return[$row['type']][$row[$order_by]] = $row;
          } else {
              $return[$row[$order_by]] = $row;
          }
      }
      return $return;
  }

  public function save_question($data, $languages)
  {


      if(is_numeric($data['id'])) {
          $db_question = $this->query_question(['id'=>$data['id']]);
          $save = $db_question->first();
          if (!$save){
            $save = $this->query_question('new');
          }
      } else {
          $save = $this->query_question('new');
          if (Auth::guard('admin')->check()){
              $save->created_by_admin_id = Auth::guard('admin')->id();
          }
          if (Auth::guard('teacher')->check()){
              $save->created_by_teacher_id = Auth::guard('teacher')->id();
          }
      }



      // if (Auth::guard('admin')->check()){
      //     $save->created_by_admin_id = Auth::guard('admin')->id();
      // }
      // if (Auth::guard('teacher')->check()){
      //     $save->created_by_teacher_id = Auth::guard('teacher')->id();
      // }

      $save->test_id = $data['test_id'];
      $save->type = $data['type'];


      foreach ($languages as $lang)
      {
          if(isset($data['name'][$lang]) && $data['name'][$lang])
          {
            $save_tr = $save->translateOrnew($lang);
            $save_tr->title = $data['name'][$lang];
          }
      }

      $save->degree = $data['degree'];
      $save->status = $data['status'];
      $save->required = $data['required'];
      $save->sequence = $data['sequence'];
      // $save->correct_answer = []; // $data['correct_answer'];

      if (isset($data['options'])){$save->options = $data['options'];}

      $s = $save->save();


      if (in_array($save->type,['drop_list']))
      {
          $answers = [] ;
          $sequence = 1;
          // foreach ($data['answers'] as $a_id => $answer)
          // {
          //     App\course_answer::where('id',$answer['id'])->forceDelete();
          //     $answers[$a_id] = new App\course_answer();
          //     // $answers[$a_id]->id = $answer['id'];
          //     $answers[$a_id]->status = intval(@$answer['status']);
          //     $answers[$a_id]->sequence = $sequence;
          //
          //     foreach ($languages as $lang)
          //     {
          //         $answers_tr[$a_id] = $answers[$a_id]->translateOrnew($lang);
          //         $answers_tr[$a_id]->name = strval(@$answer['name'][$lang]);
          //     }
          //     $sequence++;
          // }
          // $save->answers()->saveMany($answers);


          QuestionAnswer::where('question_id',$save->id)->forceDelete();

          $correctAnswers = [];

          foreach ($data['answers'] as $key => $answer)
          {

              $courseAnswer = new QuestionAnswer();
              $courseAnswer->question_id = $save->id;
              $courseAnswer->status = intval(@$answer['status']);
              $courseAnswer->sequence = $sequence;


              foreach ($languages as $lang)
              {
                  if(isset($answer['name'][$lang]) && $data['name'][$lang]){
                    $answers_tr = $courseAnswer->translateOrnew($lang);
                    $answers_tr->title = strval(@$answer['name'][$lang]);
                  }
              }

              $sequence++;

              $courseAnswer->save();

              if ( isset($answer['is_correct']) ) {
                  // $correctAnswers = array_merge( $correctAnswers, ['a'.$key => $courseAnswer->id] ) ;
                  $correctAnswers =  ['a'.$key => $courseAnswer->id] ;
              }

          }

          $save->correct_answers = $correctAnswers;
          $save->save();


      }
      return $save->id;

  }

  public function delete_question($id)
  {
      $db_question = $this->query_question(['id' => $id]);
      $row = $db_question->first();
      if (!empty($row))
      {
          $row->forceDelete();
      }
  }

  public function query_question($action = 'new')
  {
      return ($action == 'new') ? new \App\Models\Question() : \App\Models\Question::where($action) ;
  }



}
