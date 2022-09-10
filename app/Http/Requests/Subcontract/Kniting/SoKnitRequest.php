<?php

namespace App\Http\Requests\Subcontract\Kniting;

use Illuminate\Foundation\Http\FormRequest;

class SoKnitRequest extends FormRequest
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
               'exch_rate'=>'bail|required',
		];
	}
}