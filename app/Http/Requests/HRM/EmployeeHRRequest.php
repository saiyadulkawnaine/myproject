<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeHRRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'bail|required|max:100',
            'company_id'  => 'bail|required',
            'department_id'  => 'bail|required',
            'designation_id'  => 'bail|required',
            'date_of_birth'  => 'bail|required',
            'gender_id'  => 'bail|required',
            'date_of_join'  => 'bail|required',
            'probation_days' => 'bail|required',
            'national_id'   => 'bail|required',
            'salary'  => 'bail|required',
            'contact'  => 'bail|required',
            'address'  => 'bail|required',
            'religion'  => 'bail|required',
            //'status_id'  => 'bail|required',
            //'code'        => 'bail|required|max:100',
            
        ];
    }
}
