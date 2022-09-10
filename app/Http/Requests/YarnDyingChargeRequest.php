<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YarnDyingChargeRequest extends FormRequest
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
          'company_id'            => 'bail|required|min:1',
          'yarncount_id'          => 'bail|required|min:1',
          //'composition_id'        => 'bail|required|min:1',
          'colorrange_id'         => 'bail|required|min:1',
		  //'yarntype_id'         => 'bail|required|min:1',
          //'production_process_id' => 'bail|required|min:1',
          'rate'                  => 'bail|required|numeric',
          'uom_id'                => 'bail|required|min:1',
        ];
    }
}
