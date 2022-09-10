<?php

namespace App\Http\Requests\Production\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class ProdBatchTrimRequest extends FormRequest
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
          'itemclass_name'=>'bail|required',
          'itemclass_id'=>'bail|required',
          'qty'=>'bail|required',
          'uom_id'=>'bail|required',
          'wgt_per_unit'=>'bail|required',
          'wgt_qty'=>'bail|required',
        ];
    }
}
