<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacturaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'venta_id'      => 'required|exists:ventas,id',
            'rfc'           => [
                'required',
                'regex:/^([A-ZÑ&]{3,4}) ?-?([0-9]{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12][0-9]|3[01])) ?-?([A-Z\d]{2})([A\d])$/i'
            ],
            'razon_social'  => 'required|string|max:255',
            'uso_cfdi'      => [
                'required',
                'in:G01,G02,G03,P01,D01,D02,D03,D04,D05,D06,D07,D08,D09,D10' // Ajusta según catálogo SAT vigente
            ],
        ];
    }

    public function messages()
    {
        return [
            'venta_id.required' => 'La venta es obligatoria.',
            'venta_id.exists' => 'La venta seleccionada no existe.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.regex' => 'El RFC no tiene un formato válido.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.max' => 'La razón social no debe exceder 255 caracteres.',
            'uso_cfdi.required' => 'El uso de CFDI es obligatorio.',
            'uso_cfdi.in' => 'El uso de CFDI seleccionado no es válido.',
        ];
    }
}
