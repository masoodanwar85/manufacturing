<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreSalesOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		abort_if(Gate::denies('sales_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
 			'customerID' => 'required',
 			'orderDate' => 'required|date',
			'productID.*' => 'required',
			'quantity.*' => 'required',
			'salePrice.*' => 'required'
 		];
 	}

 	public function messages()
 	{
 		return [
 			'customerID.required' => 'Customer is required',
 			'orderDate.required' => 'Sales order date is required',
			'orderDate.date' => 'Date is invalid',
			'productID.*.required' => 'Product is required',
			'quantity.*.required' => 'Quantity is required',
			'salePrice.*.required' => 'Sale Price is required'
 		];
 	}
}
