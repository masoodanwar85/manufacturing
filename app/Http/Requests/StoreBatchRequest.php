<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		abort_if(Gate::denies('batch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
 			'batchName' => 'required',
 			'startDate' => 'required|date',
			'endDate' => 'required|date'
 		];
 	}

 	public function messages()
 	{
 		return [
 			'batchName.required' => 'Batch Name is required',
 			'startDate.required' => 'Start Date is required',
			'startDate.date' => 'Start Date is invalid',
			'endDate.required' => 'End Date is required',
			'endDate.date' => 'End Date is invalid'
 		];
 	}
}
