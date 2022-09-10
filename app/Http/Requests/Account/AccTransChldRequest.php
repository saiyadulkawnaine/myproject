<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccTransChldRequest extends FormRequest
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
            
            'acc_year_id'        => 'bail|required',
            'company_id'        => 'bail|required',
            'trans_date'        => 'bail|required|date',
            'trans_type_id'        => 'bail|required',
            'acc_chart_ctrl_head_id'        => 'bail|required',
            'code'        => 'bail|required',
            //'amount_debit'        => 'bail|required_if:amount_credit,null',
            
            //'exch_rate'        => 'bail|required',
            //'party_id'        => 'bail|required',
            //'bill_no'        => 'bail|required',

        ];
    }
}
