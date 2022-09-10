<?php

namespace App\Http\Requests\Production\Garments;

use Illuminate\Foundation\Http\FormRequest;

class ProdGmtDlvToEmbRequest extends FormRequest
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
            'produced_company_id' => 'bail|required',
        ];
    }
}
