<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccChartCtrlHeadRequest extends FormRequest
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
            'acc_chart_sub_group_id'        => 'bail|required',
            'name'        => 'bail|required|max:100',
            'code'        => 'bail|required|min:6|max:6',
            'ctrlhead_type_id'  => 'bail|required',
            'currency_id'   => 'bail|required_if:ctrlhead_type_id,1',
            'is_cm_expense'   => 'bail|required_if:statement_type_id,2',
            'expense_type_id'   => 'bail|required_if:statement_type_id,2',
        ];
            
        
    }
}
