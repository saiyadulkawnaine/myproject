<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemAccountRequest extends FormRequest
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
          'itemcategory_id'     => 'bail|required_if:identity,1,7,8,9,10',
          'itemclass_id'        => 'bail|required_if:identity,1,7,8,9,10',
          'item_nature_id'      => 'bail|required_if:identity,1',
          'yarncount_id'        => 'required_if:identity,1',
          'yarntype_id'         => 'required_if:identity,1',
          //'composition_id'    => 'required_if:identity,1',
          'item_description'    => 'bail|required_if:identity,7,8,9,10',
          'specification'       => 'bail|required_if:identity,7,8,9',
          //'color_id'            => 'bail|required_if:identity,7',
          //'size_id'             => 'bail|required_if:identity,9',
          //'gmt_position'        => 'bail|required_if:identity,9',
          //'gmt_category'        => 'bail|required_if:identity,9',
          'uom_id'              => 'bail|required_if:identity,1,7,8,9',
        ];
    }
}
