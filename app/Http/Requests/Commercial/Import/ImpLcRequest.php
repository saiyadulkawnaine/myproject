<?php

namespace App\Http\Requests\Commercial\Import;

use Illuminate\Foundation\Http\FormRequest;

class ImpLcRequest extends FormRequest
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
            'menu_id' => 'bail|required',
            'tenor' => 'bail|required',
            'supplier_id' => 'bail|required',
            'bank_account_id' => 'bail|required_if:lc_type_id,1,2',
        ];
    }

    public function messages()
    {
        return [
            'bank_account_id.required_if' => 'Bank Limit A/C required for Margin LC or Back to Back LC',
        ];
    }

}
