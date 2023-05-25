<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class UpdateDeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('delivery_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
            'deliveryDate' => 'required',
            'routeID' => 'required',
            'godownID' => 'required',
            'transportID' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'deliveryDate.required' => 'Delivery Date is required',
            'routeID.required' => 'Delivery Route is required',
            'godownID.required' => 'Delivery Godown is required',
            'transportID.required' => 'Delivery Transport is required',
        ];
    }
}
