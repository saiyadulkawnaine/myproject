<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerBranchShipdayRequest extends FormRequest
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
          'buyer_branch_id'   => 'bail|required|min:1',
          'day_name'          => 'bail|required|max:100',
        ];
    }
}
