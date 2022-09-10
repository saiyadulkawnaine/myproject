<?php

namespace App\Http\Requests\Bom;

use Illuminate\Foundation\Http\FormRequest;

class BudgetYarnDyeingRequest extends FormRequest
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
          'budget_id'             => 'bail|required',
          'budget_fabric_id'      => 'bail|required',
          'production_process_id' => 'bail|required',
          'amount'                => 'bail|required',
        ];
    }
}
