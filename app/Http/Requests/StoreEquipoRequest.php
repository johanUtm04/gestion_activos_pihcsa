<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreEquipoRequest extends FormRequest
{
    public function authorize() { return true; } 

    public function rules()
    {
        return [
            'marca_id'           => 'required|integer|exists:marcas,id',
            'modelo'             => 'required|string|max:100',
            'tipo_activo_id'     => 'required|integer|exists:tipo_activos,id',
            'sistema_operativo'  => 'required|string|max:50',
            'serial'             => 'nullable|string|max:255',
            'usuario_id'         => 'required|integer|exists:users,id',
            'ubicacion_id'       => 'nullable|integer|exists:ubicaciones,id',
            'valor_inicial'      => 'nullable|numeric|min:0',
            'fecha_adquisicion'  => 'required|date',
            'vida_util_estimada' => 'required|string|max:255',
        ];
    }

    /**
     * Método que se ejecuta DESPUÉS de validar. 
     */
    public function validatedData()
    {
        $data = $this->validated();

        // Lógica de Serial Interna encapsulada
        if (empty($data['serial'])) {
            $data['serial'] = 'INT-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        } else {
            $data['serial'] = strtoupper($data['serial']);
        }

        $data['valor_inicial'] = $data['valor_inicial'] ?? 0;

        return $data;
    }
}