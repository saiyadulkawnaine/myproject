<?php

namespace App\Http\Requests\Commercial\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExpPreCreditRequest extends FormRequest
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
             'company_id' => 'bail|required',
             'cr_date' => 'bail|required',
             'loan_type_id' => 'bail|required',
             'loan_no' => 'bail|required',
             'commercial_head_id' => 'bail|required',
             'bank_account_id' => 'bail|required',
             'tenor' => 'bail|required',
             'rate' => 'bail|required',
             'maturity_date' => 'bail|required',
        ];
    }
}
