<?php

namespace App\Http\Requests\Production\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class ProdBatchFinishQcRollRequest extends FormRequest
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
          'prod_batch_finish_qc_id'=>'bail|required',
          //'prod_batch_roll_id'=>'bail|required',
          //'qty'=>'bail|required',
          //'gsm_weight'=>'bail|required',
          //'dia_width'=>'bail|required',
        ];
    }
}
