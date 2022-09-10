<?php

namespace App\Http\Requests\Inventory\DyeChem;

use Illuminate\Foundation\Http\FormRequest;

class InvDyeChemRcvItemRequest extends FormRequest
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
            'inv_dye_chem_rcv_id'        => 'bail|required',
            'po_dye_chem_item_id'        => 'bail|required_if:receive_against_id,7',
            'inv_pur_req_item_id'        => 'bail|required_if:receive_against_id,109',
            'item_account_id'            => 'bail|required_if:receive_against_id,0',
            'qty'                        => 'bail|required',
            'rate'                       => 'bail|required',
            'amount'                     => 'bail|required',
            'exch_rate'                  => 'bail|required'
        ];
            
        
    }
}
