<?php

namespace App\Http\Requests\Subcontract\Embelishment;

use Illuminate\Foundation\Http\FormRequest;

class SoEmbRequest extends FormRequest
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
			'company_id' => 'bail|required',
               'buyer_id' => 'bail|required',
               
               'sales_order_no'  => 'bail|required',
               'currency_id'  => 'bail|required',
               'exch_rate' => 'bail|required',
               'production_area_id' => 'bail|required',
		];
	}
}