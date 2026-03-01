<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'tipo_evento'       => 'required|string',
            'tipo_evento_input' => 'required_if:tipo_evento,OTRO_VALOR|nullable|string|max:255',
            'usuario_id'        => 'required|exists:users,id',
            'fecha_evento'      => 'required|date',
            'contexto'          => 'nullable|string',
            'costo'             => 'nullable|numeric',
        ];
    }

    // Aquí resolvemos lo del "OTRO_VALOR" antes de llegar al controlador
    public function getCleanData()
    {
        $data = $this->validated();
        
        $data['tipo_evento_final'] = ($this->tipo_evento === 'OTRO_VALOR') 
            ? $this->tipo_evento_input 
            : $this->tipo_evento;

        return $data;
    }
}