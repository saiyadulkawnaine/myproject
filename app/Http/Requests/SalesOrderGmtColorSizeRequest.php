<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderGmtColorSizeRequest extends FormRequest
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
          'sale_order_country_id'   => 'bail|required',
		  'style_gmt_id'           => 'bail|required',
		  'style_color_id'           => 'bail|required',
          'style_size_id'           => 'bail|required',
        ];
    }
}
