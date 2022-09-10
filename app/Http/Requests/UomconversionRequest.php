<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UomconversionRequest extends FormRequest
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
            'uom_id' => 'bail|required|integer',
            'uom_to' => 'bail|required|integer',
            'coversion_factor' => 'bail|required|numeric',
        ];
    }
}
