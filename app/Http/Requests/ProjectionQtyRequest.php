<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectionQtyRequest extends FormRequest
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
          'style_gmt_id'    => 'bail|required',
          'qty'             => 'bail|required',
          'rate'            => 'bail|required',
          'amount'          => 'bail|required',
          'projection_country_id'      => 'bail|required',
        ];
    }
}
