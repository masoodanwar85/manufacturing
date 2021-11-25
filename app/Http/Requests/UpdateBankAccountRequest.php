<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateBankAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		abort_if(Gate::denies('bank_account_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
			 'bankID' => 'required|numeric',
             'accountTitle' => 'required',
             'accountNumber' => 'required',
			 'branchCode' => 'required',
			 'branchName' => 'required',
			 'branchLocation' => 'required'
         ];
     }

     public function messages()
     {
         return [
             'bankID.required' => 'Bank is required',
             'accountTitle.required' => 'Account title is required',
			 'accountNumber.required' => 'Account number is required',
			 'branchCode.required' => 'Branch Code is required',
			 'branchName.required' => 'Branch name is required',
			 'branchLocation.required' => 'Branch location is required'
         ];
     }
}
