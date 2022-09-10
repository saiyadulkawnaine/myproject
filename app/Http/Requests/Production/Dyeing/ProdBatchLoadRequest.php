<?php

namespace App\Http\Requests\Production\Dyeing;

use Illuminate\Foundation\Http\FormRequest;

class ProdBatchLoadRequest extends FormRequest
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
          'load_date'=>'bail|required',
          'load_time'=>'bail|required',
          //'load_posting_date'=>'bail|required',
          'tgt_hour'=>'bail|required',
        ];
    }
}
