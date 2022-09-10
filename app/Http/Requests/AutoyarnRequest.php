<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoyarnRequest extends FormRequest
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
            'fabric_nature_id' => 'bail|required|min:1',
            //'fabric_type' => 'bail|required|min:1',
            //'itemclass_id' => 'bail|required|min:1',
            'construction_id' => 'bail|required|min:1',
        ];
    }
}
