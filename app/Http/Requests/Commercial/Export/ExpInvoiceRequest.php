<?php

namespace App\Http\Requests\Commercial\Export;

use Illuminate\Foundation\Http\FormRequest;

class ExpInvoiceRequest extends FormRequest
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
            'invoice_no' => 'unique:exp_invoices,invoice_no,'.$this->id
        ];
    }
}
