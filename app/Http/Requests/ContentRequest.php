<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\helpers\UtilHelper;

class ContentRequest extends FormRequest
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
                'content_type_id' => 'required|exists:content_types,id',
                'alias' => 'required|string|max:150',
                'parent_id' => 'required|integer',
                'sort' => 'nullable|integer|gt:0',
                'description' => 'nullable|string|max:1000',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'is_active' => 'required|integer|in:0,1',
                'is_free' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'video' => 'nullable|string|max:500',
                'course_id' => 'required',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'language' => 'required|string|exists:languages,locale',
                'title' => 'required|string|max:100',
                'content_type_id' => 'required|exists:content_types,id',
                'alias' => 'required|string|max:150',
                'parent_id' => 'required|integer',
                'sort' => 'nullable|integer|gt:0',
                'description' => 'nullable|string|max:1000',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'is_active' => 'required|integer|in:0,1',
                'is_free' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'video' => 'nullable|string|max:500',
                'course_id' => 'required',
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
      $this->merge([ 'alias' => UtilHelper::validateAlias(UtilHelper::convertToLower($title)) ]);
      $this->merge([ 'description' => UtilHelper::formatNormal($this->description) ]);
      $this->merge([ 'meta_description' => UtilHelper::formatNormal($this->meta_description) ]);
      $this->merge([ 'meta_keywords' => UtilHelper::formatNormal($this->meta_keywords) ]);

      // now there is only one course in the future will read this from courses table
      $this->merge([ 'course_id' => 1 ]);


    }

    public function attributes()
    {
        return [
          'language' => __('words.language'),
          'title' => __('words.title'),
          'content_type_id' => __('content.content_type'),
          'alias' => __('words.alias'),
          'parent_id' => __('words.parent'),
          'sort' => __('words.sort'),
          'description' => __('words.description'),
          'image' => __('words.image'),
        ];
    }

}
