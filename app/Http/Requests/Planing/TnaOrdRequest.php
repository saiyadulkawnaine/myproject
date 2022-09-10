<?php
namespace App\Http\Requests\Planing;

use Illuminate\Foundation\Http\FormRequest;

class TnaOrdRequest extends FormRequest
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
            'sales_order_id'        => 'bail|required',
            'tna_task_id'        => 'bail|required',
        ];
    }
}
