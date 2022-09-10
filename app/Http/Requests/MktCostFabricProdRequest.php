<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MktCostFabricProdRequest extends FormRequest
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
          'mkt_cost_id'          => 'bail|required',
          'mkt_cost_fabric_id'   => 'bail|required',
          'production_process_id'           => 'bail|required',
		      'colorrange_id'   => 'bail|required_if:production_area_id,5,20',
		  'yarncount_id'   => 'bail|required_if:production_area_id,5',
          'cons'                 => 'bail|required',
          'rate'                 => 'bail|required',
          'amount'               => 'bail|required',
        ];
    }
}
