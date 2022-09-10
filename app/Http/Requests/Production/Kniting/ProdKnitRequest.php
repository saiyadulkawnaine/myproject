<?php

namespace App\Http\Requests\Production\Kniting;

use Illuminate\Foundation\Http\FormRequest;

class ProdKnitRequest extends FormRequest
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
            'prod_date'=>'bail|required',
            'basis_id'=>'bail|required',
            'supplier_id'=>'bail|required',
            'shift_id'=>'bail|required',
       
        ];
    }
}
