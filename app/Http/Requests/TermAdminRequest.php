<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TermAdminRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'language' => 'required',
            'title' => 'required|string|max:150',
            'is_active' => 'required|integer|in:0,1',
            'sort' => 'nullable|integer|unique:terms,sort,'.$this->id,
        ];
    }


    public function attributes()
    {
        return [
            'language' => __('words.language'),
            'title' => __('words.title'),
            'sort' => __('words.sort'),
            'is_active' => __('words.is_active'),
        ];
    }


}
