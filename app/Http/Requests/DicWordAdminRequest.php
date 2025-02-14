<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\helpers\UtilHelper;

class DicWordAdminRequest extends FormRequest
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
                'letter_id' => 'required|integer|exists:letters,id',
                'title' => 'required|string|max:50',
                'dic_content_id' => 'required|integer|exists:dic_contents,id',
                'is_active' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'pdf' => 'nullable|file|mimes:pdf|max:3000',
                'sound' => 'nullable|file|mimes:mpga,mp4,webm,wav,mp3|max:3000',
                'video' => 'nullable|string|max:500',
                'html' =>  'nullable|string|max:50000',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'language' => 'required|string|exists:languages,locale',
                'letter_id' => 'required|integer|exists:letters,id',
                'title' => 'required|string|max:50',
                'dic_content_id' => 'required|integer|exists:dic_contents,id',
                'is_active' => 'required|integer|in:0,1',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:300',
                'pdf' => 'nullable|file|mimes:pdf|max:3000',
                'sound' => 'nullable|file|mimes:mpga,mp4,webm,wav,mp3|max:3000',
                'video' => 'nullable|string|max:500',
                'html' =>  'nullable|string|max:50000',
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

    }

    public function attributes()
    {
        return [
          'language' => __('words.language'),
          'letter_id' => __('project.character'),
          'title' => __('project.word'),
          'dic_content_id' => __('project.contents'),
          'image' => __('words.image'),
          'pdf' => __('words.pdf'),
          'video' => __('words.video'),
          'html' => __('words.html'),
        ];
    }

}
