<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StyleGmtColorSizeRequest extends FormRequest
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
          'style_id'              => 'bail|required|min:1',
          'style_gmt_id'          => 'bail|required|min:1',
          'style_color_id'        => 'bail|required|min:1',
          'style_size_id'         => 'bail|required|min:1',
        ];
    }
}
