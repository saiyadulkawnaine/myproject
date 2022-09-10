<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ItemAccountSupplierRateRequest extends FormRequest
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
          'item_account_supplier_id' => 'bail|required|min:1',
		  //'supplier_id' => 'bail|required|min:1',
          'date_from'          => 'bail|required',
          'date_to'          => 'bail|required',
          //'rate'          => 'bail|required',
        ];
    }
}
