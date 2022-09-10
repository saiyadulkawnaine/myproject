<?php

namespace App\Http\Requests\FAMS;

use Illuminate\Foundation\Http\FormRequest;

class AssetRecoveryRequest extends FormRequest
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
            'function_date'=>'required|bail',
            'function_time'=>'required|bail',
        ];
    }

    public function messages()
    {
        return [
            'function_date.required' => 'A Recovery Date is required',
            'function_time.required' => 'A Recovery Time is required',
        ];
    }
}
