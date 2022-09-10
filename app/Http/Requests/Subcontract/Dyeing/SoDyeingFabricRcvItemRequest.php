<?php

namespace App\Http\Requests\Subcontract\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class SoDyeingFabricRcvItemRequest extends FormRequest
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
               'so_dyeing_ref_id'  => 'bail|required',
               'so_dyeing_fabric_rcv_id'  => 'bail|required',
               'qty'  => 'bail|required',
               'rate'  => 'bail|required',
               'amount'  => 'bail|required'
		];
	}
}