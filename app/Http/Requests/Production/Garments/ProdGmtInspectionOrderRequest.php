<?php

namespace App\Http\Requests\Production\Garments;

use Illuminate\Foundation\Http\FormRequest;

class ProdGmtInspectionOrderRequest extends FormRequest
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
          //'qty'=>'bail|required_without_all:re_check_qty,failed_qty',
          //'re_check_qty'=>'bail|required_without_all:qty,failed_qty',
         // 'failed_qty'=>'bail|required_without_all:qty,failed_qty',
          // 'qty.required_without'=>'Required Field1',
         // 're_check_qty.required_without'=>'Required Field2',
         // 'failed_qty.required_without'=>'Required Field3'
        ];
    }
}
