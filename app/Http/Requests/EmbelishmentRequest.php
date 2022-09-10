<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmbelishmentRequest extends FormRequest
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
		  'production_process_id' => 'bail|required',
          //'name' => 'bail|required|max:100',
        ];
    }
}
