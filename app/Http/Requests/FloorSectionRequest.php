<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FloorSectionRequest extends FormRequest
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
            //'floor_id'    => 'bail|required|max:10',
            'section_id'    => 'bail|required|min:1',
        ];
    }
}
