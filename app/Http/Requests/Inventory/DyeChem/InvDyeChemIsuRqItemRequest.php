<?php

namespace App\Http\Requests\Inventory\DyeChem;

use Illuminate\Foundation\Http\FormRequest;

class InvDyeChemIsuRqItemRequest extends FormRequest
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
            'sub_process_id'        => 'bail|required',
            'item_account_id'        => 'bail|required',
            'qty'        => 'bail|required',
            'sort_id'        => 'bail|required',
        ];
            
        
    }
}
