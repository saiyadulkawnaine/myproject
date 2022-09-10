<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermsConditionRequest extends FormRequest
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
          'term'    => 'bail|required',
          'menu_id' => 'bail|required',
          'sort_id' => 'bail|required',
        ];
    }
}
