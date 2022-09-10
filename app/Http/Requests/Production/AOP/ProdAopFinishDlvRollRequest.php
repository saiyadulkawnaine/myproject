<?php

namespace App\Http\Requests\Production\AOP;

use Illuminate\Foundation\Http\FormRequest;

class ProdAopFinishDlvRollRequest extends FormRequest
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
            'prod_finish_dlv_id'=>'bail|required',
            //'prod_knit_qc_id'=>'bail|required',
       
        ];
    }
}
