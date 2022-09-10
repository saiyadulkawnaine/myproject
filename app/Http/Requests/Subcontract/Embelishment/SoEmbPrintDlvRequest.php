<?php

namespace App\Http\Requests\Subcontract\Embelishment;

use Illuminate\Foundation\Http\FormRequest;

class SoEmbPrintDlvRequest extends FormRequest
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
   'dlv_date' => 'bail|required',
   'company_id' => 'bail|required',
   'buyer_id' => 'bail|required',

  ];
 }
}
