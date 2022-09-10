<?php

namespace App\Http\Requests\Inventory\FinishFabric;

use Illuminate\Foundation\Http\FormRequest;

class InvFinishFabRcvItemRequest extends FormRequest
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
            'inv_finish_fab_rcv_id'  => 'bail|required',
            'prod_finish_dlv_roll_id'  => 'bail|required',
        ];
            
        
    }
}
