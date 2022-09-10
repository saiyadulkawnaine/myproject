<?php

namespace App\Http\Requests\Production\Garments;

use Illuminate\Foundation\Http\FormRequest;

class ProdGmtCartonEntryRequest extends FormRequest
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
          'company_id'      => 'bail|required',
          'location_id'       => 'bail|required',
        ];
    }
}
