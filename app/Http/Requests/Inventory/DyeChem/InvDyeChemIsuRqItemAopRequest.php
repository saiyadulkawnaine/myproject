<?php

namespace App\Http\Requests\Inventory\DyeChem;
use Illuminate\Foundation\Http\FormRequest;
class InvDyeChemIsuRqItemAopRequest extends FormRequest
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
            'inv_dye_chem_isu_rq_id'        => 'bail|required',
            //'so_aop_id'        => 'bail|required',
            //'print_type_id'        => 'bail|required',
            'item_account_id'        => 'bail|required',
            'rto_on_paste_wgt'        => 'bail|required',
            'qty'        => 'bail|required',
            'sort_id'        => 'bail|required',
        ];
    }
}
