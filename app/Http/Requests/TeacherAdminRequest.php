<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\helpers\UtilHelper;
use App\helpers\DateHelper;

class TeacherAdminRequest extends FormRequest
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
                'title' => 'required|string|max:150|unique:teachers,title',
                'gender_id' => 'required|exists:genders,id',
                'user_name' => 'nullable|string|max:100',
                'password' => 'nullable|string|min:6|max:12|confirmed',

                'title_last' => 'nullable|string|max:150',
                'birthdate' => 'nullable|string|max:10|date',
                'country_id' => 'nullable|integer|exists:countries,id',
                'description' => 'nullable|array',
                'description.*' => 'nullable|string|max:200',
                'certificate' => 'nullable|array',
                'certificate.*' => 'nullable|string|max:200',
                'experience_years' => 'nullable|string|max:100',

                'value' => 'nullable|array',
                'value.*' => 'nullable|string|max:200',

                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:200',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'title' => 'required|string|max:150|unique:teachers,title,'.$this->id,
                'gender_id' => 'required|exists:genders,id',
                'user_name' => 'required|string|max:100',
                'password' => 'nullable|string|min:6|max:12|confirmed',

                'title_last' => 'nullable|string|max:150',
                'birthdate' => 'nullable|string|max:10|date',
                'country_id' => 'nullable|integer|exists:countries,id',
                'description' => 'nullable|array',
                'description.*' => 'nullable|string|max:200',
                'certificate' => 'nullable|array',
                'certificate.*' => 'nullable|string|max:200',
                'experience_years' => 'nullable|string|max:100',

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
          'description' => __('words.description'),
          'certificate' => __('project.certificate'),
          'experience_years' => __('project.experience_years'),

          'value' => __('project.extra_value'),
          'value.*' => __('project.extra_value'),

        ];
    }

}
