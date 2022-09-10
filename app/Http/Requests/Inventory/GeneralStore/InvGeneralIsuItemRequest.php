<?php

namespace App\Http\Requests\Inventory\GeneralStore;

use Illuminate\Foundation\Http\FormRequest;

class InvGeneralIsuItemRequest extends FormRequest
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
            'inv_general_isu_rq_item_id'        => 'bail|required',
            'item_account_id'            => 'bail|required',
            'qty'                        => 'bail|required',
        ];
            
        
    }
}
