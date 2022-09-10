<?php

namespace App\Http\Requests\Commercial\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExpDocSubmissionRequest extends FormRequest
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
            'negotiation_date'=>'bail|required_if:submission_type_id,1',
        ];
    }
}
