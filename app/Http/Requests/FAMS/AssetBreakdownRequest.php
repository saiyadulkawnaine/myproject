<?php

namespace App\Http\Requests\FAMS;

use Illuminate\Foundation\Http\FormRequest;

class AssetBreakdownRequest extends FormRequest
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
            'breakdown_date'=>'required|bail',
            'breakdown_time'=>'required|bail',
        ];
    }
}
