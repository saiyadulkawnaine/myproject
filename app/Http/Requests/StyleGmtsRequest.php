<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StyleGmtsRequest extends FormRequest
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
          'style_id'          => 'bail|required|min:1',
          'item_account_id'   => 'bail|required|min:1',
          'gmt_qty'           => 'bail|required',
          'item_complexity'   => 'bail|required',
          //'gmt_catg'          => 'bail|required',
        ];
    }
}
