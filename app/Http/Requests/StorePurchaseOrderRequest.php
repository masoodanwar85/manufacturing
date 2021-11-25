<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StorePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		abort_if(Gate::denies('purchase_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
			'supplierID' => 'required_without:customerID',
			'customerID' => 'required_without:supplierID',
 			'batchID' => 'required',
			'purchaseOrderDate' => 'required|date',
			'productID.*' => 'required',
			'quantity.*' => 'required',
			'exchangeRate.*' => 'required',
			'perUnitPrice.*' => 'required'
 		];
 	}

 	public function messages()
 	{
 		return [
 			'supplierID.required' => 'Supplier is required',
			'customerID.required' => 'Customer is required',
 			'batchID.required' => 'Batch is required',
			'purchaseOrderDate.required' => 'Purchase order date is required',
			'purchaseOrderDate.date' => 'Date is invalid',
			'productID.*.required' => 'Product is required',
			'quantity.*.required' => 'Quantity is required',
			'exchangeRate.*.required' => 'Exchange Rate is required',
			'perUnitPrice.*.required' => 'Per Unit Price is required'
 		];
 	}
}
