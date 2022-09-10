<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderRequest extends FormRequest
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
          'job_id'          => 'bail|required',
          //'projection_id'   => 'bail|required',
          'sale_order_no'   => 'bail|required',
          'place_date'      => 'bail|required',
          'receive_date'    => 'bail|required|after:place_date',
          'ship_date'       => 'bail|required|after:receive_date',
          'rate'         => 'bail|required',
          'order_status'         => 'bail|required',
        ];
    }
}
