<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WashChargeRequest extends FormRequest
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
		  'embelishment_id'  => 'bail|required|min:1',
          'embelishment_type_id'  => 'bail|required|min:1',
         // 'composition_id'        => 'bail|required|min:1',
         // 'color_range_id'        => 'bail|required|min:1',
		  //'embelishment_size_id'        => 'bail|required|min:1',
		  'embelishment_size_id'   => 'bail|required_if:production_area_id,45,50',
          'rate'                  => 'bail|required|numeric',
          'uom_id'                => 'bail|required|min:1',
        ];
    }
}
