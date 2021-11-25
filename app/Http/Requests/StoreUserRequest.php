<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
			 'email' => 'required|email',
			 'password' => 'required',
			 'confirmPassword' => 'required|same:password',
			 'roleID' => 'required'
         ];
     }

     public function messages()
     {
         return [
             'name.required' => 'Name is required',
			 'email.required' => 'Email is required',
			 'email.email' => 'Email is invalid',
			 'password.required' => 'Password is required',
			 'confirmPassword.required' => 'Confirm Password is required',
			 'roleID.required' => 'Role is required'
         ];
     }
}
