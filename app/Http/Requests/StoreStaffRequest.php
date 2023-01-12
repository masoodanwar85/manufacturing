<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function authorize()
     {
		abort_if(Gate::denies('staff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
             'isActive' => 'required',
             'staffName' => 'bail|required',
             'staffTypeID' => 'required|numeric',
             'paymentFrequencyID' => 'required|numeric',
			 'dateJoined' => 'required|date'
         ];
     }

     public function messages()
     {
         return [
             'isActive.required' => 'Active or InActive is required',
             'staffName.required' => 'Staff name is required',
             'staffTypeID.required' => 'Staff Type is required',
			 'dateJoined.required' => 'Date Joined is required',
			 'dateJoined.date' => 'Date Joined must be a valid date',
             'paymentFrequencyID.required' => 'Payment Frequency is required'
         ];
     }
}
