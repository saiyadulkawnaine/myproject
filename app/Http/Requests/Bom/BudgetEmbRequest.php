<?php

namespace App\Http\Requests\Bom;

use Illuminate\Foundation\Http\FormRequest;

class BudgetEmbRequest extends FormRequest
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
          'budget_id'           => 'bail|required',
          //'embelishment_id'       => 'bail|required',
         //'embelishment_type_id'  => 'bail|required',
          'cons'                  => 'bail|required',
          'rate'                  => 'bail|required',
          'amount'                => 'bail|required',
        ];
    }
}
