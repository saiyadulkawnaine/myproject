<?php

namespace App\Http\Requests\Inventory\FinishFabric;

use Illuminate\Foundation\Http\FormRequest;

class InvFinishFabRcvPurFabricRequest extends FormRequest
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
             'inv_finish_fab_rcv_id'  => 'bail|required',
             'po_fabric_item_id'  => 'bail|required',
             'req_dia'  => 'bail|required',
             'fabric_color_id'  => 'bail|required',
             'sales_order_id'  => 'bail|required',
             'colorrange_id'  => 'bail|required',
             'gsm_weight'  => 'bail|required',
             'dia'  => 'bail|required',
             'stitch_length'  => 'bail|required',
             'shrink_per'  => 'bail|required',
             'rate'  => 'bail|required',
        ];
            
        
    }
}
