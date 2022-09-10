<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StyleSizeMsureValRequest extends FormRequest
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
       /*   'buyer_id'              => 'bail|required|min:1',
          'receive_date'          => 'bail|required',
          'style_ref'             => 'bail|required|max:50',
          'dept_category_id'      => 'bail|required|min:1',
          'productdepartment_id'  => 'bail|required|min:1',
          'season_id'             => 'bail|required|min:1',
          'uom_id'                => 'bail|required|min:1',
          'team_id'               => 'bail|required|min:1',
          'teammember_id'         => 'bail|required|min:1',*/
        ];
    }
}
