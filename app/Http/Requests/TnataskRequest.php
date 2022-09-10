<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TnataskRequest extends FormRequest
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
            'tna_task_id' => 'bail|required',
            'task_name' => 'bail|required|max:250',
            'is_auto_completion' => 'bail|required|max:3',
        ];
    }
}
