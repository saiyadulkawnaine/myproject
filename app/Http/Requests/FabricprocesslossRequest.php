<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FabricprocesslossRequest extends FormRequest
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
            'buyer_id'          => 'bail|required|min:1',
            'fabric_nature_id'  => 'bail|required|min:1',
            'composition_id'    => 'bail|required|min:1',
            'colorrange_id'     => 'bail|required|min:1',
        ];
    }
}
