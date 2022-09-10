<?php

namespace App\Http\Requests\Production\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class ProdBatchUnloadRequest extends FormRequest
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
          'id'=>'bail|required',
          'unload_date'=>'bail|required',
          'unload_time'=>'bail|required',
          'unload_posting_date'=>'bail|required',
        ];
    }
}
