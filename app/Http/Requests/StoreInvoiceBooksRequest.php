<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreInvoiceBooksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('invoice_books_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
            'bookType' => 'required',
            'bookNumber' => 'required|numeric',
            'startPage' => 'required|numeric',
            'endPage' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'bookType.required' => 'Book Type is required',
            'bookNumber.required' => 'Book Number is required',
            'startPage.required' => 'Start Page is required and should be numeric',
            'endPage.required' => 'Start Page is required and should be numeric'
        ];
    }
}
