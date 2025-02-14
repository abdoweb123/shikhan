<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\helpers\helper;

class TestRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $statuses = lookupService()->getActiveStatuses()->pluck('id');
        $getQuestions = lookupService()->getActiveGetQuestionsStatuses()->pluck('id');
        $testTypes = lookupService()->getActiveTestTypes()->pluck('id');



        switch($this->method())
        {
            case 'GET':
            case 'POST':
            {
              return [
                'locale' => 'required|string|exists:language,alies',
                'title' => 'required|string|max:150',
                'alies' => 'required|string|max:150',
                'teacher_id' => 'required|integer|exists:teachers,id',
                'test_type_id' => ['required', 'integer', Rule::in($testTypes)],
                'get_questions' => ['required', 'string', Rule::in($getQuestions)],
                'duration' => 'nullable|integer',
                'percentage' => 'required|numeric|max:100',
                'status_id' => ['required','integer', Rule::in($statuses) ],
                'lesson_ids' => 'required|array',
                'lesson_ids.*' => 'integer|exists:lessons,id',
                'show_count' => 'required|integer|min:1',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'locale' => 'required|string|exists:language,alies',
                'title' => 'required|string|max:150',
                'alies' => 'required|string|max:150',
                'teacher_id' => 'required|integer|exists:teachers,id',
                'test_type_id' => ['required', 'integer', Rule::in($testTypes)],
                'get_questions' => ['required', 'string', Rule::in($getQuestions)],
                'duration' => 'nullable|integer',
                'percentage' => 'required|numeric|max:100',
                'status_id' => ['required','integer', Rule::in($statuses)],
                'lesson_ids' => 'required|array',
                'lesson_ids.*' => 'integer|exists:lessons,id',
                'show_count' => 'required|integer|min:1',
              ];
            }
            case 'PATCH':
            default:break;
        }


    }

    protected function prepareForValidation()
    {
        $this->merge(['alies' => helper::validateAlias( helper::convertToLower( helper::formatNormal($this->alies)))]);
    }

    public function attributes()
    {
        return [
          'locale' => __('general.language'),
          'title' => __('general.title'),
          'alies' => __('general.alies'),
          'teacher_id' => __('domain.teacher'),
          'test_type_id' => __('domain.test_type'),
          'get_questions' => __('domain.get_questions'),
          'percentage' => __('general.percentage'),
          'status_id' => __('general.status'),
          'lesson_ids' => __('domain.lesson'),
          'lesson_ids.*' => __('domain.domain'),
          'show_count' => __('domain.show_count'),
        ];
    }

}
