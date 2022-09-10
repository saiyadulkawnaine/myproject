<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GmtsProcessLossRequest extends FormRequest
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
          'company_id'          => 'bail|required|min:1',
          'buyer_id'            => 'bail|required|min:1',
          'gmt_qty_range_start' => 'bail|required|integer',
          'gmt_qty_range_end'   => 'bail|required|integer',
        ];
    }
}
