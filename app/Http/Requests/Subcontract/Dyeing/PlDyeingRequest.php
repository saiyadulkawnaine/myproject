<?php

namespace App\Http\Requests\Subcontract\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class PlDyeingRequest extends FormRequest
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
          'company_id'  => 'bail|required',
          'pl_date'  => 'bail|required',
          'supplier_id'  => 'bail|required',
          'machine_id'  => 'bail|required',
          ];
	}
}