<?php


namespace App\Imports;

use App\Models\Test;
use App\site;
use App\libraries\_commonfn;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class Questions implements ToCollection, WithHeadingRow
{
//    private $site_id;
//    private $course_id;
//    private $course;
//    private $questions_for;
//
//    public function __construct(String $site_id, int $course_id, ?String $questions_for)
//    {
//        $this->site_id = $site_id;
//        $this->course_id = $course_id;
//        $site = site::where('id',$site_id)->firstOrFail();
//
//        $this->questions_for = $questions_for;
//
//
//        if ($this->questions_for == 'term'){
//          $this->course = $site->terms()->findOrFail($course_id);
//        } else {
//          $this->course = $site->courses()->findOrFail($course_id);
//        }
//
//    }
//
//    public function collection(Collection $rows)
//    {
//        $questions = [];
//
//        $courseLanguages = $this->course->translations()->pluck('locale');
//
//        foreach ($rows as $row)
//        {
//
//            $answers = [];
//            $name = [];
//
//            foreach ($courseLanguages as $locale)
//            {
//                $name[$locale] = strval(@$row['name_'.$locale]);
//            }
//
//            $options = explode(',',$row['options']);
//            $correct_answer = explode(',',$row['correct_answer']);
//
//
//
//            // prepare answers
//            $allLanguages = getLanguages();
//            $answer = null;
//            for( $i=1; $i<=10; $i++ ) {
//                foreach ($allLanguages as $language) {
//                    $answerColumnTitle = 'a'.$i.'_'.$language->alies; // a1,a2,loop
//                    if ( isset($row[ $answerColumnTitle ]) && $row[ $answerColumnTitle ] ) {
//                        $answer['name'][$language->alies] = $row[ $answerColumnTitle ];
//                    }
//                }
//
//                if (isset($answer['name'])){
//                  if (in_array( $i, $correct_answer )) { // set is_correct only if correct otherwise no is_correct in array
//                    $answer['is_correct'] = true;
//                  }
//                  $answer['status'] = 1;
//                  array_push($answers, $answer);
//                  $answer = null;
//              }
//            }
//
//
//
//            if($row['id']){
//              $questions[$row['id']] = [
//                  'id' => $row['id'],
//                  'type' => 'drop_list', // $row['type'],
//                  'status' => $row['status'], // $row['status'],
//                  'required' => $row['required'], // $row['required'],
//                  'degree' => $row['degree'], // $row['degree'],
//                  'name' => $name,
//                  'answers' => $answers,
//                  'options' => [], // $row['type'] == 'range' ? ['min' => intval(@$options[0]),'max' => intval(@$options[1])] : [],
//                  'correct_answer' => array_map('intval',$correct_answer),
//              ];
//            }
//
//
//        }
//
//        (new _commonfn)->update_questions($this->course->id, $courseLanguages, $questions, $this->questions_for);
//
//    }


    public function __construct(private Test $test)
    {

    }

    public function collection(Collection $rows)
    {
        $questions = [];

        $testLanguages = $this->test->translations()->pluck('locale');

        foreach ($rows as $row)
        {

            $answers = [];
            $name = [];

            foreach ($testLanguages as $locale)
            {
                $name[$locale] = strval(@$row['name_'.$locale]);
            }

            $options = explode(',',$row['options']);
            $correct_answer = explode(',',$row['correct_answer']);



            // prepare answers
            $allLanguages = getLanguages();
            $answer = null;
            for( $i=1; $i<=10; $i++ ) {
                foreach ($allLanguages as $language) {
                    $answerColumnTitle = 'a'.$i.'_'.$language->alias; // a1,a2,loop
                    if ( isset($row[ $answerColumnTitle ]) && $row[ $answerColumnTitle ] ) {
                        $answer['name'][$language->alias] = $row[ $answerColumnTitle ];
                    }
                }


                if (isset($answer['name'])){
                    if (in_array( $i, $correct_answer )) { // set is_correct only if correct otherwise no is_correct in array
                        $answer['is_correct'] = true;
                    }
                    $answer['status'] = 1;
                    array_push($answers, $answer);
                    $answer = null;
                }
            }



            if($row['id']){
                $questions[$row['id']] = [
                    'id' => $row['id'],
                    'type' => $row['type'], // 'drop_list', // $row['type'],
                    'status' => $row['status'], // $row['status'],
                    'required' => $row['required'], // $row['required'],
                    'degree' => $row['degree'], // $row['degree'],
                    'name' => $name,
                    'answers' => $answers,
                    'options' => [], // $row['type'] == 'range' ? ['min' => intval(@$options[0]),'max' => intval(@$options[1])] : [],
                    'correct_answer' => array_map('intval',$correct_answer),
                ];
            }


        }


        (new \App\Services\QuestionUpdateService)->updateQuestions($this->test, $testLanguages, $questions);

    }
}
