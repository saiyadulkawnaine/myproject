<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AopChargeRequest extends FormRequest
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
          'company_id'       => 'bail|required|min:1',
		  'autoyarn_id'       => 'bail|required|min:1',
          'from_gsm'         => 'bail|required|numeric',
          'to_gsm'           => 'bail|required|numeric',
		  'embelishment_type_id'           => 'bail|required|numeric',
		  'from_coverage'           => 'bail|required|numeric',
		  'to_coverage'           => 'bail|required|numeric',
		  'from_impression'           => 'bail|required|numeric',
		  'to_impression'           => 'bail|required|numeric',
          'rate'    => 'bail|required|numeric',
        ];
    }
}
