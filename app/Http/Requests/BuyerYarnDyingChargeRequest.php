<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerYarnDyingChargeRequest extends FormRequest
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
          'yarn_dying_charge_id' => 'bail|required',
          'buyer_id'             => 'bail|required|min:1',
          'rate'                 => 'bail|required|numeric',
        ];
    }
}
