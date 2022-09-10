<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PoKnitServiceRequest extends FormRequest
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
          'po_date'       => 'bail|required',
          'supplier_id'     => 'bail|required',
          'basis_id'     => 'bail|required',
          'pay_mode'        => 'bail|required',
		  'currency_id'     => 'bail|required',
		  'exch_rate'       => 'bail|required',
		   
        ];
    }
}
