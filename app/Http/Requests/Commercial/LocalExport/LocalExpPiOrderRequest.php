<?php

namespace App\Http\Requests\Commercial\LocalExport;

use Illuminate\Foundation\Http\FormRequest;

class LocalExpPiOrderRequest extends FormRequest
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
            //'qty' => 'required|numeric|min:0|not_in:0',
        ];
    }
}
