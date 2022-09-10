<?php

namespace App\Http\Requests\Inventory\DyeChem;

use Illuminate\Foundation\Http\FormRequest;

class InvDyeChemIsuRqAddRequest extends FormRequest
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
            'root_id'        => 'bail|required',
            'rq_date'        => 'bail|required',
        ];
            
        
    }
}
