<?php

namespace App\Http\Requests\Production\AOP;

use Illuminate\Foundation\Http\FormRequest;

class ProdAopBatchFinishProgRequest extends FormRequest
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
          'production_process_id'=>'bail|required',
          'machine_id'=>'bail|required',
          'load_date'=>'bail|required',
          'load_time'=>'bail|required',
          'unload_date'=>'bail|required',
          'unload_time'=>'bail|required',
          'posting_date'=>'bail|required',
        ];
    }
}
