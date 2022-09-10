<?php

namespace App\Http\Requests\Bom;

use Illuminate\Foundation\Http\FormRequest;

class BudgetTrimRequest extends FormRequest
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
          'itemclass_id'         => 'bail|required',
          //'description'           => 'bail|required',
          //'specification'         => 'bail|required',
          //'item_size'             => 'bail|required',
          //'sup_ref'               => 'bail|required',
          'uom_id'                => 'bail|required',
          //'cons'                  => 'bail|required',
          //'rate'                  => 'bail|required',
          //'amount'                => 'bail|required',
        ];
    }
}
