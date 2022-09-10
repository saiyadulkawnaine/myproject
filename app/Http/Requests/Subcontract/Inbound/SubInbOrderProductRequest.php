<?php

namespace App\Http\Requests\Subcontract\Inbound;

use Illuminate\Foundation\Http\FormRequest;

class SubInbOrderProductRequest extends FormRequest
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
			'item_account_id'=>'bail|required',
               'qty' => 'bail|required',
               'rate' => 'bail|required',
               'delivery_date'=>'bail|required'
		];
	}
}