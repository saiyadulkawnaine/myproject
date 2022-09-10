<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ItemAccountSupplierFeatRequest extends FormRequest
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
          'feature_point_id' => 'bail|required|min:1',
          'available_id' => 'bail|required|min:1',
          'mandatory_id' => 'bail|required|min:1',
        ];
    }
}
