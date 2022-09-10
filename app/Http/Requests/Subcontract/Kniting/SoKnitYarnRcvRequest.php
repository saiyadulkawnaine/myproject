<?php

namespace App\Http\Requests\Subcontract\Kniting;

use Illuminate\Foundation\Http\FormRequest;

class SoKnitYarnRcvRequest extends FormRequest
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
               'receive_date'  => 'bail|required',
               'so_knit_id'  => 'bail|required'
		];
	}
}