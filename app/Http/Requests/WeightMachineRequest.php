<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeightMachineRequest extends FormRequest
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
          'machine_no'    => 'bail|required',
          'work_station_name' => 'bail|required',
          'machine_ip' => 'bail|required',
        ];
    }
}
