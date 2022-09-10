<?php

namespace App\Http\Requests\Subcontract\AOP;

use Illuminate\Foundation\Http\FormRequest;

class SoAopFabricIsuRequest extends FormRequest
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
               'so_aop_id'  => 'bail|required'
		];
	}
}