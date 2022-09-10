<?php

namespace App\Http\Requests\Commercial\LocalExport;

use Illuminate\Foundation\Http\FormRequest;

class LocalExpDocSubBankRequest extends FormRequest
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
            //'maturity_rcv_date'=>'date_format:Y-m-d|before_or_equal:today',
        ];
    }
}
