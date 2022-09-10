<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapacityDistRequest extends FormRequest
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
          'company_id'      => 'bail|required',
          'prod_type_id'    => 'bail|required',
          'prod_source_id'  => 'bail|required',
          'year'            => 'bail|required',
          'week_id'         => 'bail|required',
          'mkt_smv'         => 'bail|required',
          'prod_smv'        => 'bail|required',
          'mkt_pcs'         => 'bail|required',
          'prod_pcs'        => 'bail|required',
        ];
    }
}
