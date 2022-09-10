<?php

namespace App\Http\Requests\Production\AOP;

use Illuminate\Foundation\Http\FormRequest;

class ProdAopBatchProcessRequest extends FormRequest
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
          'asset_quantity_cost_id'=>'bail|required',
          'supervisor_id'=>'bail|required',
          'shift_id'=>'bail|required',
          'prod_date'=>'bail|required',
        ];
    }
}
