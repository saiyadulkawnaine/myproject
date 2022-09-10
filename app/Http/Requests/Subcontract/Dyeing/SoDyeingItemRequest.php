<?php

namespace App\Http\Requests\Subcontract\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class SoDyeingItemRequest extends FormRequest
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
			'gmtspart_id'=>'bail|required',
               'fabric_look_id'=>'bail|required',
               'fabric_shape_id'=>'bail|required',
               'gsm_weight'=>'bail|required',
               'colorrange_id'=>'bail|required',
               'uom_id'=>'bail|required',
               'qty' => 'bail|required',
               'rate' => 'bail|required',
               'amount' => 'bail|required',
               'delivery_date'=>'bail|required'
		];
	}
}