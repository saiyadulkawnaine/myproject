<?php

namespace App\Http\Requests\Subcontract\AOP;

use Illuminate\Foundation\Http\FormRequest;

class SoAopItemRequest extends FormRequest
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
            'colorrange_id'=>'bail|required_if:so_aop_items.id,not null',
            'uom_id'=>'bail|required',
            'bill_for'=>'bail|required',
            'qty' => 'bail|required',
            'rate' => 'bail|required',
            'amount' => 'bail|required',
            'delivery_date'=>'bail|required_if:so_aop_items.id,not null',
		];
	}
}