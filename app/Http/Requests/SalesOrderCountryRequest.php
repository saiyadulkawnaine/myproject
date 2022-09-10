<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderCountryRequest extends FormRequest
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
		  'job_id'             => 'bail|required',
          'sale_order_id'   => 'bail|required',
          'country_id'      => 'bail|required',
          //'style_gmt_id'    => 'bail|required',
          'fabric_looks'    => 'bail|required',
          'country_ship_date'       => 'bail|required',
          'breakdown_basis' => 'bail|required',
        ];
    }
}
