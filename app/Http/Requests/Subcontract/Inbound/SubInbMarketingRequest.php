<?php

namespace App\Http\Requests\Subcontract\Inbound;

use Illuminate\Foundation\Http\FormRequest;

class SubInbMarketingRequest extends FormRequest
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
			'company_id'=>'bail|required',
			'production_area_id'=>'bail|required',
			'team_id'=>'bail|required',
			'teammember_id'=>'bail|required',
			'buyer_id'=>'bail|required',
			'currency_id'=>'bail|required',
			'refered_by'=>'bail|required',
		];
	}
}