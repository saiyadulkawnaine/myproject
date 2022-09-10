<?php

namespace App\Http\Requests\Subcontract\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class SoDyeingBomOverheadRequest extends FormRequest
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
               'so_dyeing_bom_id'  => 'bail|required',
               'acc_chart_ctrl_head_id'  => 'bail|required',
               'cost_per'  => 'bail|required',
               'amount'  => 'bail|required'
		];
	}
}