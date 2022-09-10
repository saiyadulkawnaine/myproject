<?php

namespace App\Http\Requests\Production\AOP;

use Illuminate\Foundation\Http\FormRequest;

class ProdAopBatchFinishProgRollRequest extends FormRequest
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
          'prod_batch_finish_prog_id'=>'bail|required',
          'prod_aop_batch_roll_id'=>'bail|required',
        ];
    }
}
