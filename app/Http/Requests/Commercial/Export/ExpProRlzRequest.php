<?php

namespace App\Http\Requests\Commercial\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExpProRlzRequest extends FormRequest
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
            'exp_doc_submission_id' => 'unique:exp_pro_rlzs,exp_doc_submission_id,'.$this->id
        ];
    }
}
