<?php

namespace App\Http\Requests\FAMS;

use Illuminate\Foundation\Http\FormRequest;

class AssetReturnDetailRequest extends FormRequest
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
            // 'type_id'=>'bail|required',
            // 'production_area_id'=>'bail|required_if:type_id,5',
            // 'iregular_supplier'=>'required_without:supplier_id'
        ];
    }
}
