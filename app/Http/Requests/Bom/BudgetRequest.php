<?php

namespace App\Http\Requests\Bom;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
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
          //'style_id'              => 'bail|required',
		  'job_id'              => 'bail|required',
          //'costing_unit_id'          => 'bail|required',
          'budget_date'             => 'bail|required',
          //'currency_id'           => 'bail|required',
          //'incoterm_id'           => 'bail|required',
          //'incoterm_place'        => 'bail|required',
          //'offer_qty'             => 'bail|required',
          //'est_ship_date'         => 'bail|required',
          //'op_date'               => 'bail|required',
        ];
    }
}
