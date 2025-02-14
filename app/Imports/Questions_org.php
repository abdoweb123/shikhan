<?php


namespace App\Imports;

use App\site;
use App\libraries\_commonfn;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class Questions_org implements ToCollection, WithHeadingRow
{
    public function __construct(String $site_alias,int $course_id)
    {
        $this->site_alias = $site_alias;
        $this->course_id = $course_id;
        $site = site::where('alias',$site_alias)->firstOrFail();
        $this->course = $site->courses()->findOrFail($course_id);
    }

    public function collection(Collection $rows)
    {
        $questions = [];
        foreach ($rows as $row)
        {
            $answers = [];
            $name = [];
            foreach ($this->course->languages as $locale)
            {
                $name[$locale] = strval(@$row['name_'.$locale]);
            }

            $options = explode(',',$row['options']);
            $correct_answer = explode(',',$row['correct_answer']);

            $questions[$row['id']] = [
                'id' => $row['id'],
                'type' => $row['type'],
                'status' => $row['status'],
                'required' => $row['required'],
                'degree' => $row['degree'],
                'name' => $name,
                'answers' => $answers,
                'options' => $row['type'] == 'range' ? ['min' => intval(@$options[0]),'max' => intval(@$options[1])] : [],
                'correct_answer' => array_map('intval',$correct_answer),
            ];
        }
        (new _commonfn)->update_questions($this->course->id,$this->course->languages,$questions);
    }
}
