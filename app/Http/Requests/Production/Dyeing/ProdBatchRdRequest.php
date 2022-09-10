<?php

namespace App\Http\Requests\Production\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class ProdBatchRdRequest extends FormRequest
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
          'batch_no'=>'bail|required',
          'batch_date'=>'bail|required',
          'root_batch_no'=>'bail|required',
          'root_batch_id'=>'bail|required',
          'machine_no'=>'bail|required',
          'machine_id'=>'bail|required',
        ];
    }
}
