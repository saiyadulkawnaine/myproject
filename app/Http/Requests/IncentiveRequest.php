<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncentiveRequest extends FormRequest
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
          'company_id'              => 'bail|required|max:10',
          'location_id'             => 'bail|required|max:10',
          'division_id'             => 'bail|required|max:10',
          'department_id'           => 'bail|required|max:10',
          'section_id'              => 'bail|required|max:10',
          'production_process_id'   => 'bail|required|max:10',
          'basis_id'                => 'bail|required|max:3',
          'designation_id'          => 'bail|required|max:10',
        ];
    }
}
