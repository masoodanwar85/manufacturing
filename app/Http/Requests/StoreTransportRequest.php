<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreTransportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function authorize()
     {
		abort_if(Gate::denies('transport_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
			 'name' => 'required',
			 'owner' => 'required',
             'vehicleNumber' => 'required'
         ];
     }

     public function messages()
     {
         return [
             'name.required' => 'Name is required',
			 'owner.required' => 'Owner name is required',
             'vehicleNumber.required' => 'Vehicle number is required'
         ];
     }
}
