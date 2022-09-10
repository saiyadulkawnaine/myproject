<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
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
            'name'              => 'bail|required|max:150',
            'code'              => 'bail|required|max:10',
            'gmt_category_id'   => 'bail|required|min:1',
            'dept_category_id'  => 'bail|required|min:1',
            'gmtspart_id'       => 'bail|required|min:1',
            'fabrication_id'    => 'bail|nullable|integer',
            'smv_basis_id'      => 'bail|required|min:1',
            'resource_id'       => 'bail|required|min:1',
            'machine_smv'       => 'bail|required',
            'manual_smv'        => 'bail|required',
        ];
    }
}
