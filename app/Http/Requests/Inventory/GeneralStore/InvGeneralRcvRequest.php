<?php

namespace App\Http\Requests\Inventory\GeneralStore;

use Illuminate\Foundation\Http\FormRequest;

class InvGeneralRcvRequest extends FormRequest
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
            'company_id'        => 'bail|required',
            'supplier_id'        => 'bail|required',
            'location_id'        => 'bail|required',
            'receive_basis_id'        => 'bail|required',
            'receive_against_id'        => 'bail|required',
            'challan_no'        => 'bail|required',
            'receive_date'        => 'bail|required',
        ];
            
        
    }
}
