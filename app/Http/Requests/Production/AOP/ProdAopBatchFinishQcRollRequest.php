<?php

namespace App\Http\Requests\Production\AOP;

use Illuminate\Foundation\Http\FormRequest;

class ProdAopBatchFinishQcRollRequest extends FormRequest
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
          'prod_aop_batch_finish_qc_id'=>'bail|required',
          //'prod_batch_roll_id'=>'bail|required',
          //'qty'=>'bail|required',
          //'gsm_weight'=>'bail|required',
          //'dia_width'=>'bail|required',
        ];
    }
}
