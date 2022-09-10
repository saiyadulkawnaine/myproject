<?php

namespace App\Http\Requests\Subcontract\Inbound;

use Illuminate\Foundation\Http\FormRequest;

class SubInbServiceRequest extends FormRequest
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
			'est_delv_date'=>'bail|required',
			'qty'=>'bail|required',
			'rate'=>'bail|required',
			'amount'=>'bail|required',
		];
	}
}