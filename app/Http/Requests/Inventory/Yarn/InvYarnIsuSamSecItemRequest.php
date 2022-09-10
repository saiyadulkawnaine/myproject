<?php

namespace App\Http\Requests\Inventory\Yarn;

use Illuminate\Foundation\Http\FormRequest;

class InvYarnIsuSamSecItemRequest extends FormRequest
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
            'inv_yarn_item_id'          => 'bail|required',
            'store_id'          => 'bail|required',
            'qty'          => 'bail|required',
            'sale_order_no'          => 'bail|required_if:isu_basis_id,6',
            'style_ref'          => 'bail|required_if:isu_basis_id,6,7',
            'style_sample_id'          => 'bail|required_if:isu_basis_id,6,7',
        ];
            
        
    }
}
