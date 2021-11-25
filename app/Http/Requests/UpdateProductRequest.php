<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		abort_if(Gate::denies('product_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
             'productName' => 'bail|required|max:255',
             'categoryID' => 'required|numeric',
			 'thresholdUnit' => 'required|numeric',
             'maximumUnitID' => 'required|numeric'
         ];
     }

     public function messages()
     {
         return [
             'productName.required' => 'Product is required',
             'categoryID.required' => 'Category is required',
			 'thresholdUnit.required' => 'Alert quantity is required',
             'maximumUnitID.required' => 'Product Unit is required'
         ];
     }
}
