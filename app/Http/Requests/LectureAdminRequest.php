<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\helpers\UtilHelper;
use App\helpers\DateHelper;

class LectureAdminRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        switch($this->method())
        {
            case 'GET':
            case 'POST':
            {
              return [
                'language' => 'required|string|exists:languages,locale',
                'title' => 'required|string|max:100',
                'alias' => 'required|string|max:150',
                'content_id' => 'required|integer|exists:contents,id',
                'lesson_id' => 'required|integer|exists:lessons,id',
                'lecture_type_id' => 'required|integer|exists:lecture_types,id',
                'lecture_woner_id' => 'required|integer|exists:lecture_woners,id',
                'teacher_id' => 'required|integer|exists:teachers,id',
                'max_members' => 'nullable|integer|gt:0',
                'min_members' => 'nullable|integer|gt:0',
                'date' => 'required|string|max:19|date',
                'is_active' => 'required|integer|in:0,1',
                'is_free' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'pdf' => 'nullable|file|mimes:pdf|max:3000',
                'sound' => 'nullable|file|mimes:mpga,mp4,webm,wav,mp3|max:3000',
                'video' => 'nullable|string|max:500',
                'html' =>  'nullable|string|max:50000',
                'brief' =>  'nullable|string|max:300',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'language' => 'required|string|exists:languages,locale',
                'title' => 'required|string|max:100',
                'alias' => 'required|string|max:150',
                'content_id' => 'required|integer|exists:contents,id',
                'lesson_id' => 'required|integer|exists:lessons,id',
                'lecture_type_id' => 'required|integer|exists:lecture_types,id',
                'lecture_woner_id' => 'required|integer|exists:lecture_woners,id',
                'teacher_id' => 'required|integer|exists:teachers,id',
                'max_members' => 'nullable|integer|gt:0',
                'min_members' => 'nullable|integer|gt:0',
                'date' => 'required|string|max:19|date',
                'is_active' => 'required|integer|in:0,1',
                'is_free' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'pdf' => 'nullable|file|mimes:pdf|max:3000',
                'sound' => 'nullable|file|mimes:mpga,mp4,webm,wav,mp3|max:3000',
                'video' => 'nullable|string|max:500',
                'html' =>  'nullable|string|max:50000',
                'brief' =>  'nullable|string|max:300',
              ];
            }
            case 'PATCH':
            default:break;
        }


    }

    protected function prepareForValidation()
    {

      $title = UtilHelper::formatNormal($this->title);
      $this->merge([ 'title' => $title ]);
      $this->merge([ 'alias' =>
        UtilHelper::validateAlias( UtilHelper::convertToLower( UtilHelper::formatNormal($this->alias) ) )
      ]);


      if ($this->date){
          $date = DateHelper::validateDateTime( DateHelper::DateTimeToDb( $this->date ) );
          $this->merge([ 'date' => ($date == false) ? 'x' : $date ]);
      }

    }

    public function attributes()
    {
        return [
          'language' => __('words.language'),
          'title' => __('words.title'),
          'alias' => __('words.alias'),
          'content_id' => __('project.contents'),
          'lesson_id' =>  __('project.lessons'),
          'lecture_type_id' => __('project.lecture_type'),
          'lecture_woner_id' => __('project.lecture_woner'),
          'teacher_id' => __('project.teachers'),
          'max_members' => __('project.max_members'),
          'min_members' => __('project.min_members'),
          'date' => __('words.date'),
          // 'sort' => __('words.sort'),
          'image' => __('words.image'),
          'video' => __('words.video'),
          'html' => __('words.html'),
          'brief' => __('words.brief'),
        ];
    }

}
