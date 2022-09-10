<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PoTrimItemQtyRequest extends FormRequest
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
          'po_trim_item_id'      => 'bail|required|min:1',
          'budget_trim_con_id'       => 'bail|required|min:1',
		  'qty'       => 'bail|required',
		  'rate'       => 'bail|required',
		  'amount'       => 'bail|required'
        ];
    }
}
