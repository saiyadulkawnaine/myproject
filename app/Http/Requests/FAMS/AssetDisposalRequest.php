<?php

namespace App\Http\Requests\FAMS;

use Illuminate\Foundation\Http\FormRequest;

class AssetDisposalRequest extends FormRequest
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
             'asset_quantity_cost_id'=>'unique:asset_disposals,asset_quantity_cost_id,'.$this->id,
             'buyer_id'=>'bail|required_if:disposal_type_id,1,3',
            // 'iregular_supplier'=>'required_without:supplier_id'
        ];
    }
}
