<?php

namespace App\Http\Requests\Production\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class ProdBatchRdProcessRequest extends FormRequest
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
          'production_process_id'=>'bail|required',
          'sort_id'=>'bail|required',
        ];
    }
}
