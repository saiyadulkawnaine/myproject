<?php

namespace App\Http\Requests\Production\AOP;

use Illuminate\Foundation\Http\FormRequest;

class ProdAopBatchRequest extends FormRequest
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
          'so_aop_id'=>'bail|required',
          'batch_no'=>'bail|required',
          'batch_date'=>'bail|required',
          'batch_for'=>'bail|required',
          'batch_color_id'=>'bail|required',
          'paste_wgt'=>'bail|required',
          //'fabric_wgt'=>'bail|required',
        ];
    }
}
