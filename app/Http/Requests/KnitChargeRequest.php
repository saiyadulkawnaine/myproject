<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnitChargeRequest extends FormRequest
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
          //'gmtspart_id'      => 'bail|required|min:1',
		  //'autoyarn_id'  => 'bail|required|min:1',
		  'fabric_look_id'  => 'bail|required|min:1',
          'construction_id'  => 'bail|required|min:1',
          //'composition_id'   => 'bail|required|min:1',
          //'from_gsm'         => 'bail|required|numeric',
          //'to_gsm'           => 'bail|required|numeric',
          //'yarncount_id'     => 'bail|required|min:1',
          'in_house_rate'    => 'bail|required|numeric',
          'uom_id'           => 'bail|required|min:1',
        ];
    }
}
