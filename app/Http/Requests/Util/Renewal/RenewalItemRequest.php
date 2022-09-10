<?php

namespace App\Http\Requests\Util\Renewal;

use Illuminate\Foundation\Http\FormRequest;

class RenewalItemRequest extends FormRequest
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

            'code'=>'bail| required'
        ];
    }
}
