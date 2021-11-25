<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateAccountHeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		abort_if(Gate::denies('account_head_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		return ($this->route('accountHead')->isEditable == 1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
	 public function rules()
     {
         return [
             'headName' => 'bail|required'
         ];
     }

     public function messages()
     {
         return [
             'headName.required' => 'Head name is required'
         ];
     }
}
