<?php

namespace App\Http\Requests\Commercial\CashIncentive;

use Illuminate\Foundation\Http\FormRequest;

class CashIncentiveRefRequest extends FormRequest
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
             //'exp_lc_sc_id' => 'required|unique:cash_incentive_refs',
             //'bank_file_no' => 'required|unique:cash_incentive_refs',
        ];
    }
}
