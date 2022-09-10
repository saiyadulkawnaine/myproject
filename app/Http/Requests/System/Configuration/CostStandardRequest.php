<?php

namespace App\Http\Requests\System\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class CostStandardRequest extends FormRequest
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
               'configuration_type_id'  => 'bail|required',
               'company_id'  => 'bail|required'
        ];
    }
}
