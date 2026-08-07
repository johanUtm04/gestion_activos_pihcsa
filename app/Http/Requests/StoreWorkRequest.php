<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipo_evento'       => 'required|string',
            'tipo_evento_input' => 'required_if:tipo_evento,OTRO_VALOR|nullable|string|max:255',

            'usuario_id'        => 'required|exists:users,id',
            'fecha_evento'      => 'required|date',

            'proveedor'         => 'nullable|string|max:255',

            'contexto'          => 'nullable|string',
            'costo'             => 'nullable|numeric|min:0',

            'orden_servicio'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'factura'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public function getCleanData()
    {
        $data = $this->validated();

        $data['tipo_evento_final'] =
            ($this->tipo_evento === 'OTRO_VALOR')
                ? $this->tipo_evento_input
                : $this->tipo_evento;

        return $data;
    }
}