<?php

namespace App\Http\Requests\Production\Kniting;

use Illuminate\Foundation\Http\FormRequest;

class ProdKnitDlvRollRequest extends FormRequest
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
            'prod_knit_dlv_id'=>'bail|required',
            //'prod_knit_qc_id'=>'bail|required',
       
        ];
    }
}
