<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeycontrolParameterRequest extends FormRequest
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
            'keycontrol_id' => 'bail|required|max:10',
            'parameter_id' => 'bail|required|max:10',
            'from_date' => 'bail|required|max:10',
            'from_date' => 'bail|required|max:10',
            'value' => 'bail|required|max:14',
        ];
    }
}
