<?php

namespace App\Http\Requests\Util\Renewal;

use Illuminate\Foundation\Http\FormRequest;

class RenewalItemDocRequest extends FormRequest
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
            'sort_id' => 'bail|required|unique:renewal_item_docs,sort_id',
        ];
    }
}
