<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SewingCapacityDateRequest extends FormRequest
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
          'sewing_capacity_id'     => 'bail|required',
          //'capacity_date'   => 'bail|required',
          'day_status'      => 'bail|required',
          'resource_qty'   => 'bail|required',
          //'mkt_cap_mint'         => 'bail|required',
          //'mkt_cap_pcs'         => 'bail|required',
          //'prod_cap_mint'        => 'bail|required',
          //'prod_cap_pcs'        => 'bail|required',
        ];
    }
}
