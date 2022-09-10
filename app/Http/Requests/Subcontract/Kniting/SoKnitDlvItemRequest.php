<?php

namespace App\Http\Requests\Subcontract\Kniting;

use Illuminate\Foundation\Http\FormRequest;

class SoKnitDlvItemRequest extends FormRequest
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
               'so_knit_dlv_id'  => 'bail|required'
		];
	}
}