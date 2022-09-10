<?php

namespace App\Http\Requests\System\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class CostStandardHeadRequest extends FormRequest
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
               'acc_chart_ctrl_head_id'  => 'bail|required',
               'cost_per'  => 'bail|required'
        ];
    }
}
