<?php

namespace App\Http\Requests\Production\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class ProdBatchRequest extends FormRequest
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
          'company_id'=>'bail|required',
          'batch_no'=>'bail|required',
          'batch_date'=>'bail|required',
          'batch_for'=>'bail|required',
          'machine_no'=>'bail|required',
          'machine_id'=>'bail|required',
          'fabric_color_id'=>'bail|required',
          'colorrange_id'=>'bail|required',
        ];
    }
}
