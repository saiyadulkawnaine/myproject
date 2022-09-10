<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
          'name' => 'bail|required|max:100',
          'cgroup_id' => 'bail|required|integer',
          'code' => 'bail|required|max:5',
          'sort_id' => 'bail|required|integer',
          'nature_id' => 'bail|required|integer',
          'legal_status_id' => 'bail|required|integer',
        ];
    }
}
