<?php
namespace App\Http\Requests\Workstudy;

use Illuminate\Foundation\Http\FormRequest;

class WstudyLineSetupDtlOrdRequest extends FormRequest
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
            'sewing_start_at' => 'bail|required',
            'sewing_end_at' => 'bail|required',
        ];
    }
}
