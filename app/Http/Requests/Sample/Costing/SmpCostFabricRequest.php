<?php

namespace App\Http\Requests\Sample\Costing;

use Illuminate\Foundation\Http\FormRequest;

class SmpCostFabricRequest extends FormRequest
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
          'smp_cost_id'          => 'bail|required',
          'style_fabrication_id'          => 'bail|required',
          'gsm_weight'          => 'bail|required',
        ];
    }
}
