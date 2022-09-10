<?php

namespace App\Http\Requests\Subcontract\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class SoDyeingDlvRequest extends FormRequest
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
               'issue_date'  => 'bail|required',
               'currency_id'  => 'bail|required',
		];
	}
}