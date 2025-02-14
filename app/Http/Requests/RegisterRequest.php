<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\helpers\UtilHelper;

class RegisterRequest extends FormRequest
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
                'type_id' => 'required|in:2,3',
                // 'title' => 'required|string|max:150|unique:students,title',
                'user_name' => 'required|string|max:20',

                'email' => 'required|string|max:150|unique:users,email',
                'password' => 'required|string|min:6|max:12|confirmed',
                // 'accept_terms' => 'accepted',
                'country_id'=>'nullable',
                'native_language_id'=>'nullable',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
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
      $this->merge([ 'name' => $title ]);
    }

    public function attributes()
    {
        return [
          'type_id' => __('words.user_type'),
          'title' => __('words.name'),
          'gender_id' => __('words.gender'),
          'user_name' => __('words.user_name'),
          'email' => __('words.email'),
          'password' => __('words.password'),
          'accept_terms' => __('words.terms'),
        ];
    }

}
