<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GmtssampleRequest extends FormRequest
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
            'name'    => 'bail|required|max:100',
            'type_id' => 'bail|required|min:1',
            'sort_id' => 'bail|required|min:1',
        ];
    }
}