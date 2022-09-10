<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PoYarnDyeingRequest extends FormRequest
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
          'company_id'      => 'bail|required|min:1',
          'source_id'       => 'bail|required|min:1',
          'pay_mode'        => 'bail|required',
		  'currency_id'     => 'bail|required',
		  'exch_rate'       => 'bail|required',
		  'supplier_id'     => 'bail|required',
        ];
    }
}
