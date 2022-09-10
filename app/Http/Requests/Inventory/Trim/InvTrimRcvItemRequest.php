<?php

namespace App\Http\Requests\Inventory\Trim;

use Illuminate\Foundation\Http\FormRequest;

class InvTrimRcvItemRequest extends FormRequest
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
            'inv_trim_rcv_id'        => 'bail|required',
            'po_trim_item_id'        => 'bail|required_if:receive_against_id,7',
            'qty'                        => 'bail|required',
            'rate'                       => 'bail|required',
            'amount'                     => 'bail|required',
            'exch_rate'                  => 'bail|required'
        ];
            
        
    }
}
