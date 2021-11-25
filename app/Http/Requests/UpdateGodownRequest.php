<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateGodownRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function authorize()
     {
		abort_if(Gate::denies('godown_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
             'address' => 'required'
         ];
     }

     public function messages()
     {
         return [
             'name.required' => 'Godown name is required',
             'address.required' => 'Godown address is required'
         ];
     }
}
