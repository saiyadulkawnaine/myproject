<?php

namespace App\Http\Requests\Commercial\LocalExport;

use Illuminate\Foundation\Http\FormRequest;

class LocalExpInvoiceRequest extends FormRequest
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
            'local_invoice_no' => 'unique:local_exp_invoices,local_invoice_no,'.$this->id
        ];
    }
}
