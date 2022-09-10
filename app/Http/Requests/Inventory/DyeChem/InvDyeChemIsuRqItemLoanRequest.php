<?php

namespace App\Http\Requests\Inventory\DyeChem;

use Illuminate\Foundation\Http\FormRequest;

class InvDyeChemIsuRqItemLoanRequest extends FormRequest
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
            'inv_dye_chem_isu_rq_id'        => 'bail|required',
            'asset_quantity_cost_id'        => 'bail|required_if:rq_basis_id,6',
            'item_account_id'        => 'bail|required',
            'qty'        => 'bail|required',
            'sort_id'        => 'bail|required',
        ];
            
        
    }
}
