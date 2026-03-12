<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipoRequest extends FormRequest
{
    public function authorize() { return true; }

    //Validaciones
    public function rules()
    {
        $equipoId = $this->route('equipo')->id;

        return [
            'marca_id'           => 'required|exists:marcas,id',
            'tipo_activo_id'     => 'required|exists:tipo_activos,id',
            'usuario_id'         => 'required|exists:users,id',
            'modelo'             => 'nullable|string|max:100',
            'departamento_perteneciente' => 'nullable|string|max:100',
            'fecha_adquisicion'  => 'required|date',
            'fecha_inicio_uso'  => [
                'required',
                'date',
                'after_or_equal:fecha_adquisicion', 
                'before_or_equal:today',           
            ],
            'valor_inicial'      => 'nullable|numeric',
            'sistema_operativo'  => 'nullable|string',
            'serial' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipos', 'serial')->ignore($equipoId),
            ],
        ];
    }

    public function getProcessedData()
    {
        // Obtenemos solo los datos que pasaron las reglas de validación
        $data = $this->validated();

        // Logica de Vida Útil encapsulada aquí
        if ($this->filled(['vida_util_estimada', 'vida_util_unidad'])) {
            $data['vida_util_estimada'] = $this->vida_util_estimada . ' ' . $this->vida_util_unidad;
        }

        // 3. Eliminamos el campo temporal 'vida_util_unidad' para que no ensucie el update
        unset($data['vida_util_unidad']);

        return $data;
    }
}
