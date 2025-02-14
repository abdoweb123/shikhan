<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Department;
use App\helpers\UtilHelper;
use Auth;

class EmployeeRequest extends FormRequest
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
                'name' => 'required|string|max:150|unique:employees,name',
                'department_id' => 'required|integer|exists:departments,id',
                'gender_id' => 'required|integer|exists:genders,id',
                'record_no' => 'required|integer|gt:0|unique:employees',
                'job_no' => 'required|integer|gt:0|unique:employees',
                'department_date' => 'nullable|string|max:10|date',
                'job_title_id' => 'nullable|string|max:150', // |exists:job_titles,id
                'phone' => 'nullable|numeric|digits:10|unique:employees',
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:500',
              ];
            }
            case 'DELETE':
            case 'PUT':
            {
              return [
                'name' => 'required|string|max:150|unique:employees,name,'.$this->id,
                'department_id' => 'required|integer|exists:departments,id',
                'gender_id' => 'required|integer|exists:genders,id',
                'record_no' => 'required|integer|gt:0|unique:employees,record_no,'.$this->id,
                'job_no' => 'required|integer|gt:0|unique:employees,job_no,'.$this->id,
                'department_date' => 'nullable|string|max:10|date',
                'job_title_id' => 'nullable|string|max:150', // |exists:job_titles,id
                'phone' => 'nullable|numeric|digits:10|unique:employees,phone,'.$this->id,
                'image' => 'nullable|file|image|mimes:jpeg,png,gif,jpg,svg|max:500',

              ];
            }
            case 'PATCH':
            default:break;
        }


    }

    protected function prepareForValidation()
    {

        $this->merge([ 'name' => UtilHelper::formatNormal($this->name) ]);
        if ($this->department_date){
            $department_date = UtilHelper::validateDate($this->department_date);
            $this->merge([ 'department_date' => ($department_date == false) ? 'x' : UtilHelper::DateToDb($department_date) ]);
        }

        $this->merge([ 'job_title_id' => UtilHelper::formatNormal($this->job_title_id) ]);

        $this->merge([ 'gender_id' => Auth::user()->genderId ]);
        if ($this->gender_id == 1 ){
            $this->merge([ 'department_id' => Department::PARENT_MALE ]);
        }

        if ($this->gender_id == 2 ){
            $this->merge([ 'department_id' => Department::PARENT_FEMALE ]);
        }

    }

    public function attributes()
    {
        return [
          'name' => __('employee.name'),
          'gender_id' => __('words.type'),
          'record_no' => __('employee.record_no'),
          'job_no' => __('employee.job_no'),
          'department_date' =>  __('employee.department_date'),
          'job_title_id' =>  __('employee.job_title'),
          'phone' =>  __('words.mobile'),
          'image' =>  __('words.image'),
        ];
    }



}
