<?php

namespace App\Http\Requests\Commercial\LocalExport;

use Illuminate\Foundation\Http\FormRequest;

class LocalExpProRlzRequest extends FormRequest
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
            'local_exp_doc_sub_bank_id' => 'unique:local_exp_pro_rlzs,local_exp_doc_sub_bank_id,'.$this->id
        ];
    }
}
