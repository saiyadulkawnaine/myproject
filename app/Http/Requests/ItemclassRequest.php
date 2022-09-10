<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemclassRequest extends FormRequest
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
            'itemcategory_id'     => 'bail|required|min:1',
            'name'                => 'bail|required|max:100',
            'item_nature_id'      => 'bail|required|min:1',
            'uomclass_id'         => 'bail|required|min:1',
            'pre_account_req_id'  => 'bail|required|min:1',
        ];
    }
}
