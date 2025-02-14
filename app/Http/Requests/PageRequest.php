<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\helpers\UtilHelper;

class PageRequest extends FormRequest
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
                'language' => 'required|string', //
                'title' => 'required|string|max:100',
                'alias' => 'required|string|max:150',
                'parent_id' => 'required|integer',
                'description' => 'nullable',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'header' => 'nullable|string|max:500',
                'is_active' => 'nullable|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:1024',
                'video' => 'nullable|string|max:500',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'language' => 'required|string|exists:language,alies', // |exists:languages,locale
                'title' => 'required|string|max:100',
                'alias' => 'required|string|max:150',
                'parent_id' => 'required|integer',
                'description' => 'nullable',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'header' => 'nullable|string|max:500',
                'is_active' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:1024',
                'video' => 'nullable|string|max:500',
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
      // $this->merge([ 'description' => UtilHelper::formatNormal($this->description) ]);
      $this->merge([ 'meta_description' => UtilHelper::formatNormal($this->meta_description) ]);
      $this->merge([ 'meta_keywords' => UtilHelper::formatNormal($this->meta_keywords) ]);
      $this->merge([ 'header' => UtilHelper::formatNormal($this->header) ]);

    }

    public function attributes()
    {
        return [
          'language' => __('words.language'),
          'title' => __('words.title'),
          'alias' => __('words.alias'),
          'parent_id' => __('words.parent'),
          'description' => __('words.description'),
          'image' => __('words.image'),
        ];
    }

}
