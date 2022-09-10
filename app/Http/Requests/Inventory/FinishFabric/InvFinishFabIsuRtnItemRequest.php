<?php

namespace App\Http\Requests\Inventory\FinishFabric;

use Illuminate\Foundation\Http\FormRequest;

class InvFinishFabIsuRtnItemRequest extends FormRequest
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
            'inv_finish_fab_rcv_id'        => 'bail|required',
            'inv_finish_fab_isu_item_id'        => 'bail|required',
            //'item_account_id'            => 'bail|required',
            'qty'                        => 'bail|required',
            //'rate'                       => 'bail|required',
            //'amount'                     => 'bail|required',
        ];
            
        
    }
}
