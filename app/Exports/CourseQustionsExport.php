<?php

namespace App\Exports;

use App\course_question;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class CourseQustionsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    private $course_id;

    public function __construct(int $course_id)
    {
        $this->course_id = $course_id;
    }

    public function collection()
    {

          $data = DB::Table('course_questions')
              ->join('course_questions_translations', 'course_questions.id', 'course_questions_translations.question_id')
              ->join('course_answers', 'course_questions.id', 'course_answers.question_id')
              ->join('course_answers_translations', 'course_answers.id', 'course_answers_translations.answer_id')

              ->where('course_questions.course_id', $this->course_id)

              ->select('course_questions.id', 'course_questions.type','course_questions.options', 'course_questions.status', 'course_questions.required',
                  'course_questions.degree', 'course_questions_translations.name','course_questions.correct_answer',
                  'course_answers.id as course_answers_id','course_answers.sequence', 'course_answers.status as course_answers_status',
                  'course_answers_translations.name as answer_name')
              ->orderBy('course_questions.id')->get();

          $dataIds = array_unique($data->pluck('id')->toArray());

          $finalData = [];
          foreach ($dataIds as $item) {
              $children = $data->where('id', $item)->toArray();

              $firstKey = array_key_first($children);
              $firstChild = $children[$firstKey];
              $firstChild = [
                  'id' => $firstChild->id,
                  'type' => $firstChild->type,
                  'options' => $firstChild->options,
                  'status' => $firstChild->status,
                  'required' => $firstChild->required,
                  'degree' => $firstChild->degree,
                  'name_ar' => $firstChild->name,
                  'answers' => '',
              ];


              $ansewrsTitles = [];
              $i = 1;
              foreach ($children as $key => $child) {
                  $correctAnswer = json_decode($child->correct_answer, true);
                  $firstKey = array_key_first($correctAnswer);
                  $correctAnswerId = $correctAnswer[$firstKey];
                  $firstChild['correct_answer'] = $data->where('course_answers_id', $correctAnswerId)->first()->sequence;

                  $ansewrsTitles['a'.$i] = $child->answer_name;
                  $i = $i + 1;

              }

              $firstChild = array_merge($firstChild, $ansewrsTitles);

              $finalData[] = $firstChild;
          }

          return collect($finalData);

    }

    public function map($row): array
    {
        return [
            $row['id'],
            $row['type'],
            $row['answers'],
            $row['options'],
            $row['status'],
            $row['required'],
            $row['degree'],
            $row['name_ar'],
            $row['correct_answer'],
            $row['a1'],
            $row['a2'],
            isset($row['a3']) ? $row['a3'] : '',
            isset($row['a4']) ? $row['a4'] : ''
        ];
    }

    public function headings(): array
    {
        return ['id','type','answers','options','status','required','degree','name_ar','correct_answer','a1','a2','a3','a4'];
    }
}
