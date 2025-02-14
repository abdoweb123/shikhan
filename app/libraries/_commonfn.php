<?php
namespace App\libraries;

use App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session as Session ;
use App\course_question;

class _commonfn
{
    private $questions_for;

    public function update_questions($course_id, $languages, $data, $questions_for)
    {

        $this->questions_for = $questions_for;
        $sequence = 1;

        $all_questions = $this->get_question($course_id,'id');
        // if question dosent have questions_old in course_questions then $all_questions will be empty

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

            $row['course_id'] = $course_id;
            $row['degree'] = intval(@$row['degree']);
            $row['status'] = intval(@$row['status']);
            $row['required'] = intval(@$row['required']);
            $row['sequence'] = $sequence;
            $this->save_question($row,$languages);
            $sequence++;
        }

        foreach (array_keys($all_questions) as $all_element_id){
            $this->delete_question($all_element_id);
        }

    }

    public function get_question($course_id,$order_by = 'sequence')
    {

        if($this->questions_for == 'term'){
          $db_question = $this->query_question(['term_id'=>$course_id]);
        } else {
          $db_question = $this->query_question(['course_id'=>$course_id]);
        }

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

    public function save_question($data,$languages)
    {

        if(is_numeric($data['id'])) {
            $db_question = $this->query_question(['id'=>$data['id']]);
            $save = $db_question->first();
            if (!$save){
              $save = $this->query_question('new');
            }
        } else {
            $save = $this->query_question('new');
            $save->created_by = Auth::guard('admin')->user()->id;
        }



        // $save is : course_questions table
        $save->updated_by = Auth::guard('admin')->user()->id;
        if($this->questions_for == 'term'){
            $save->term_id = $data['course_id'];
        } else {
            $save->course_id = $data['course_id'];
        }

        $save->type = $data['type'];


        foreach ($languages as $lang)
        {
            if(isset($data['name'][$lang]) && $data['name'][$lang])
            {
              $save_tr = $save->translateOrnew($lang);
              $save_tr->name = $data['name'][$lang];
            }
        }
        $save->degree = $data['degree'];
        $save->status = $data['status'];
        $save->required = $data['required'];
        $save->sequence = $data['sequence'];
        $save->correct_answer = []; // $data['correct_answer'];

        if (isset($data['options'])){$save->options = $data['options'];}
        // dd($save);
        $s = $save->save();
        // return 'aaaaa';
        // dd($s,'eee');



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


            App\course_answer::where('question_id',$save->id)->forceDelete();

            $correctAnswers = [];

            foreach ($data['answers'] as $key => $answer)
            {

                $courseAnswer = new App\course_answer();
                $courseAnswer->question_id = $save->id;
                $courseAnswer->status = intval(@$answer['status']);
                $courseAnswer->sequence = $sequence;


                foreach ($languages as $lang)
                {
                    if(isset($answer['name'][$lang]) && $data['name'][$lang]){
                      $answers_tr = $courseAnswer->translateOrnew($lang);
                      $answers_tr->name = strval(@$answer['name'][$lang]);
                    }
                }

                $sequence++;

                $courseAnswer->save();

                if ( isset($answer['is_correct']) ) {
                    // $correctAnswers = array_merge( $correctAnswers, ['a'.$key => $courseAnswer->id] ) ;
                    $correctAnswers =  ['a'.$key => $courseAnswer->id] ;
                }

            }

            $save->correct_answer = $correctAnswers;
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
        return ($action == 'new') ? new App\course_question() : App\course_question::where($action) ;
    }
}
