<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreAccountHeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('account_head_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
            'headName' => 'bail|required|unique:App\Models\AccountHead,headName'
        ];
    }

    public function messages()
    {
        return [
            'headName.required' => 'Head name is required',
			'headName.unique' => 'Head name already exists'
        ];
    }
}
