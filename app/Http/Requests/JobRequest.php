<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
          'company_id'  => 'bail|required|min:1',
          'style_id'    => 'bail|required|min:1',
          //'buyer_id'    => 'bail|required|min:1',
          'currency_id' => 'bail|required|min:1',
          'exch_rate' => 'bail|required',
          //'uom_id'      => 'bail|required|min:1',
          //'season_id'   => 'bail|required|min:1',
        ];
    }
}
