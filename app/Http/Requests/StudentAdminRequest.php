<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\helpers\UtilHelper;
use App\helpers\DateHelper;

class StudentAdminRequest extends FormRequest
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
                'title' => 'required|string|max:150|unique:students,title',
                'gender_id' => 'required|exists:genders,id',
                'user_name' => 'nullable|string|max:100',
                'password' => 'nullable|string|min:6|max:12|confirmed',

                'title_last' => 'nullable|string|max:150',
                'birthdate' => 'nullable|string|max:10|date',
                'country_id' => 'nullable|integer|exists:countries,id',
                'native_language_id' => 'nullable|integer|exists:native_languages,id',
                'study_arabic_before' => 'nullable|integer|in:0,1',
                'arabic_level_before' => 'nullable|string|max:200',
                'study_arabic_aim_id' => 'nullable|integer|exists:study_arabic_aims,id',
                'study_arabic_aim_desc' => 'nullable|string|max:200',
                'skills_to_improve' => 'nullable|integer|in:0,1',
                'skills_ids' => 'nullable|array',
                'skills_ids.*' => 'exists:skills,id',
                'preferred_language_id' => 'required|integer|exists:languages,id',

                // 'term' => 'nullable|array',
                // 'term.*.*' => 'nullable|string|max:100',

                'value' => 'nullable|array',
                'value.*' => 'nullable|string|max:200',

                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:200',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'title' => 'required|string|max:150|unique:students,title,'.$this->id,
                'gender_id' => 'required|exists:genders,id',
                'user_name' => 'required|string|max:100',
                'password' => 'nullable|string|min:6|max:12|confirmed',

                'title_last' => 'nullable|string|max:150',
                'birthdate' => 'nullable|string|max:10|date',
                'country_id' => 'nullable|integer|exists:countries,id',
                'native_language_id' => 'nullable|integer|exists:native_languages,id',
                'study_arabic_before' => 'nullable|integer|in:0,1',
                'arabic_level_before' => 'nullable|string|max:200',
                'study_arabic_aim_id' => 'nullable|integer|exists:study_arabic_aims,id',
                'study_arabic_aim_desc' => 'nullable|string|max:200',
                'skills_to_improve' => 'nullable|integer|in:0,1',
                'skills_ids' => 'nullable|array',
                'skills_ids.*' => 'exists:skills,id',
                'preferred_language_id' => 'required|integer|exists:languages,id',

                // 'term' => 'nullable|array',
                // 'term.*.*' => 'nullable|string|max:100',

                'value' => 'nullable|array',
                'value.*' => 'nullable|string|max:200',

                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:200',
              ];
            }
            case 'PATCH':
            default:break;
        }


    }

    protected function prepareForValidation()
    {

      $this->merge([ 'title' => UtilHelper::formatNormal($this->title) ]);
      $this->merge([ 'title_last' => UtilHelper::formatNormal($this->title_last) ]);
      $this->merge([ 'arabic_level_before' => UtilHelper::formatNormal($this->arabic_level_before) ]);
      $this->merge([ 'study_arabic_aim_desc' => UtilHelper::formatNormal($this->study_arabic_aim_desc) ]);

      if ($this->birthdate){
          $birthdate = DateHelper::validateDate($this->birthdate);
          $this->merge([ 'birthdate' => ($birthdate == false) ? 'x' : DateHelper::DateToDb($birthdate) ]);
      }

    }

    public function attributes()
    {
        return [
          'title' => __('words.name'),
          'gender_id' => __('words.gender'),
          'user_name' => __('words.user_name'),

          'title_last' => __('words.name_last'),
          'birthdate' => __('words.birthdate'),
          'country_id' => __('words.country'),
          'native_language_id' => __('student.native_language'),
          'study_arabic_before' => __('student.name'),
          'arabic_level_before' => __('student.name'),
          'study_arabic_aim_id' => __('student.name'),
          'study_arabic_aim_desc' => __('student.name'),
          'skills_to_improve' => __('student.skills_to_improve'),
          'skills_ids' => __('project.skills'),
          'skills_ids.*' => __('project.skills'),
          'preferred_language_id' => __('words.language'),

          // 'term' => __('project.extra_term'),
          // 'term.*.*' => __('project.extra_term'),

          'value' => __('project.extra_value'),
          'value.*' => __('project.extra_value'),

        ];
    }

}
