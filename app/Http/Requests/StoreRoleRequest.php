<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		abort_if(Gate::denies('roles_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
 			'roleName' => 'required',
 			'privilegeID' => 'required'
 		];
 	}

 	public function messages()
 	{
 		return [
 			'roleName.required' => 'Role Name is required',
 			'privilegeID.required' => 'At least one privilege is required'
 		];
 	}
}
