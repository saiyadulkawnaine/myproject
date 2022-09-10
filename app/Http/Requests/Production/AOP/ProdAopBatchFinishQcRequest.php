<?php

namespace App\Http\Requests\Production\AOP;

use Illuminate\Foundation\Http\FormRequest;

class ProdAopBatchFinishQcRequest extends FormRequest
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
          'prod_aop_batch_id'=>'bail|required',
          'posting_date'=>'bail|required',
        ];
    }
}
