<?php
namespace App\Http\Requests\Planing;

use Illuminate\Foundation\Http\FormRequest;

class TnaTemplateDtlRequest extends FormRequest
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
           //
        ];
    }
}
