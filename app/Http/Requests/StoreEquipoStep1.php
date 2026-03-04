<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoStep1 extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // Cambiar a true para que permita procesar
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'marca_id'           => 'required|integer|exists:marcas,id',
            'modelo'             => 'required|string|max:100',
            'tipo_activo_id'     => 'required|integer|exists:tipo_activos,id',
            'serial'             => 'required|string|max:255|unique:equipos,serial',
            'sistema_operativo'  => 'required|string|max:35', 
            'usuario_id'         => 'required|integer|exists:users,id',
            'valor_inicial'      => 'nullable|numeric|min:0|max:99999999.99',
            'fecha_adquisicion'  => 'required|date',
            'vida_util_estimada' => 'required|integer|min:1|max:50',
        ];
        [
        'serial.unique' => '¡Error! Este número de serial ya está registrado en el sistema.',
    ];
    }

    /**
     * Mensajes personalizados (Opcional, pero recomendado para el usuario)
     */
    public function messages(): array
    {
        return [
            'serial.unique' => '¡Error! Este número de serial ya está registrado en el sistema.',
            'vida_util_estimada.integer' => 'La vida útil debe ser un número de años.',
        ];
    }
}