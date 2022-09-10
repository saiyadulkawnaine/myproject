<?php

namespace App\Http\Requests\Production\Garments;

use Illuminate\Foundation\Http\FormRequest;

class ProdGmtIronOrderRequest extends FormRequest
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
            'supplier_id'=>'bail|required_if:prod_source_id,5',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'supplier_id.required_if' => trans('If Production Source is Sub-Contract Outside than Service Provider Field Required'),
        ];
    }
}
