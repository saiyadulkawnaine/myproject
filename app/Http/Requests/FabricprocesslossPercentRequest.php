<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FabricprocesslossPercentRequest extends FormRequest
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
            'fabricprocessloss_id' => 'bail|required|min:1',
            'loss_area_id' => 'bail|required|min:1',
            'loss_percent' => 'bail|required',
        ];
    }
}
