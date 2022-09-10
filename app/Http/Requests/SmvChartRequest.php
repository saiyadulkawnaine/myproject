<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmvChartRequest extends FormRequest
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
          'company_id'              => 'bail|required|max:10',
          //'location_id'             => 'bail|required|max:10',
          'gmt_category_id'             => 'bail|required|max:3',
          'gmt_complexity_id'       => 'bail|required|max:3',
        ];
    }
}
