<?php

namespace App\Http\Requests\Subcontract\AOP;

use Illuminate\Foundation\Http\FormRequest;

class SoAopMktCostQpriceRequest extends FormRequest
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
			'so_aop_mkt_cost_id'=>'bail|required',
		];
	}
}