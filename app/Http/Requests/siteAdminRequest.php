<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class siteAdminRequest extends FormRequest
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
                'language' => 'required',
                'parent_id' => 'required',
                'title' => 'required|string|max:150', // becuase انظر و استمع و ردد rebeadted in many lessons
                'alias' => 'required|string|max:150',
                'is_active' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'image_remove' => '',
                'header' => 'required|string|max:500',
                'meta_description' => 'required|string|max:500',
                'meta_keywords' => 'required|string|max:500',
                'description' =>  'nullable|string',
                'sort' =>  'nullable|integer',
                'brief' =>  'nullable|string|max:300',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'language' => 'required',
                'parent_id' => 'required',
                'title' => 'required|string|max:150',
                'alias' => 'required|string|max:150',
                'is_active' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'image_remove' => '',
                'pdf' => 'nullable|string|max:500',
                'sound' => 'nullable|string|max:500',
                'video' => 'nullable|string|max:500',
                'sort' =>  'nullable|integer',
                'header' => 'required|string|max:500',
                'meta_description' => 'required|string|max:500',
                'meta_keywords' => 'required|string|max:500',
                'description' =>  'nullable|string',
                'brief' =>  'nullable|string|max:300',
              ];
            }
            case 'PATCH':
            default:break;
        }

    }

    protected function prepareForValidation()
    {

      // $title = formatNormal($this->title);
      // $this->merge([ 'title' => $title ]);
      $this->merge(['alias' => validateAlias(convertToLower( formatNormal($this->alias) ))]);

    }

    public function attributes()
    {
        return [
          'language' => __('words.language'),
          'title' => __('words.title'),
          'alias' => __('words.alias'),
          'sort' => __('words.sort'),
          'parent_id' => __('words.parent'),
          'image' => __('words.image'),
          'video' => __('words.video'),
          'html' => __('words.html'),
          'brief' => __('words.brief'),
          'header' => __('words.header'),
          'meta_description' => __('words.meta_description'),
          'meta_keywords' => __('words.meta_keywords'),
          'started_at' => __('words.started_at'),
          'link_zoom'  => __('words.link_zoom'),
        ];
    }


}
