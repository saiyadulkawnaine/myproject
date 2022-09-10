<?php

namespace App\Http\Requests\Inventory\DyeChem;

use Illuminate\Foundation\Http\FormRequest;

class InvDyeChemIsuRqRequest extends FormRequest
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
            //'company_id'        => 'bail|required',
            'prod_batch_id'        => 'bail|required',
            'batch_no'        => 'bail|required',
            //'location_id'        => 'bail|required',
            //'fabric_desc'        => 'bail|required',
            //'fabric_color'        => 'bail|required',
            //'colorrange_id'        => 'bail|required',
            //'batch_no'        => 'bail|required',
            //'lap_dip_no'        => 'bail|required',
            //'batch_wgt'        => 'bail|required',
            'liqure_ratio'        => 'bail|required',
            'liqure_wgt'        => 'bail|required',
            //'buyer_id'        => 'bail|required',
            'rq_date'        => 'bail|required',
        ];
            
        
    }
}
