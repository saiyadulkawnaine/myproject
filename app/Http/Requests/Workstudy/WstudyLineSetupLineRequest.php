<?php
namespace App\Http\Requests\Workstudy;

use Illuminate\Foundation\Http\FormRequest;

class WstudyLineSetupLineRequest extends FormRequest
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
           //'wstudy_line_setup_id'        => 'bail|required',
        ];
    }
}
