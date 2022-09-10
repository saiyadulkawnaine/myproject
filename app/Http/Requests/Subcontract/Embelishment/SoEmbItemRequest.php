<?php

namespace App\Http\Requests\Subcontract\Embelishment;

use Illuminate\Foundation\Http\FormRequest;

class SoEmbItemRequest extends FormRequest
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
               'embelishment_id'=>'bail|required',
               'embelishment_type_id'=>'bail|required',
               'embelishment_size_id'=>'bail|required',
               'item_account_id'=>'bail|required',
               'gmt_color'=>'bail|required',
               'gmt_size'=>'bail|required',
               'uom_id'=>'bail|required',
               'qty' => 'bail|required',
               'rate' => 'bail|required',
               'amount' => 'bail|required',
               'delivery_date'=>'bail|required'
		];
	}
}