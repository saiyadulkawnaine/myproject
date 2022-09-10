<?php

namespace App\Http\Requests\Sample\Costing;

use Illuminate\Foundation\Http\FormRequest;

class SmpCostEmbConRequest extends FormRequest
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
          'smp_cost_emb_id'  => 'bail|required'
        ];
    }
}
