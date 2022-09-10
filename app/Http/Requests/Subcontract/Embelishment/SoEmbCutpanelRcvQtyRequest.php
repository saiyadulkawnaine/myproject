<?php

namespace App\Http\Requests\Subcontract\Embelishment;

use Illuminate\Foundation\Http\FormRequest;

class SoEmbCutpanelRcvQtyRequest extends FormRequest
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
   'so_emb_cutpanel_rcv_order_id'  => 'bail|required',
  ];
 }
}
