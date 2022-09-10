<?php

namespace App\Http\Requests\Inventory\DyeChem;

use Illuminate\Foundation\Http\FormRequest;

class InvDyeChemIsuRqLoanRequest extends FormRequest
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
            'company_id'        => 'bail|required',
            'location_id'        => 'bail|required',
            'rq_basis_id'        => 'bail|required',
            'supplier_id'        => 'bail|required_if:rq_basis_id,5',
            'rq_date'        => 'bail|required',
        ];
            
        
    }
}
