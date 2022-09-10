<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StyleFabricationRequest extends FormRequest
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
          'style_id'            => 'bail|required|min:1',
          'style_gmt_id'        => 'bail|required|min:1',
          'fabric_nature_id'    => 'bail|required|min:1',
          'gmtspart_id'         => 'bail|required|min:1',
          'autoyarn_id'         => 'bail|required|min:1',
          'fabric_look_id'      => 'bail|required|min:1',
          'material_source_id'  => 'bail|required|min:1',
          //'yarncount_id'        => 'bail|required|min:1',
          'fabric_shape_id'     => 'bail|required|min:1',
          'uom_id'              => 'bail|required|min:1',
		  'embelishment_type_id' => 'bail|required_if:fabric_look_id,25',
		  'coverage'             => 'bail|required_if:fabric_look_id,25',
		  'impression'           => 'bail|required_if:fabric_look_id,25',
        ];
    }
}
