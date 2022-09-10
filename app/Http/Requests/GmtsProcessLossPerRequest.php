<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GmtsProcessLossPerRequest extends FormRequest
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
          'gmts_process_loss_id'     => 'bail|required|max:10',
          'production_process_id'   => 'bail|required|max:10',
          //'embelishment_type_id'   => 'bail|required|max:10',
          'process_loss_per'        => 'bail|required|max:14',
        ];
    }
}
