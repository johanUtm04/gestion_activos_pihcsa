<?php

namespace App\Observers;

use App\Models\Ram;
use App\Models\Historial_log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RamObserver
{
    protected $tiposMapeados = [
        'CREATED' => 'Creacion',
        'UPDATED' => 'Actualizacion',
        'DELETED' => 'Eliminacion',
        'RAM'     => 'componente-extra',
    ];

    public function created(Ram $ram): void
    {
        $equipo = $ram->equipos; 
        $esActivo = $ram->is_active;

        // Estandarizamos para que la vista lo reconozca
        $tipoRegistro = $esActivo ? 'componente-extra RAM' : 'inactivacion RAM';

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $tipoRegistro, 
                'detalles_json'     => [
                    'mensaje'          => "⚡ NUEVA RAM INSTALADA: {$ram->capacidad_gb}GB {$ram->tipo_chz}",
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    'cambios' => [
                        'Estado Inicial' => [
                            'antes'   => 'N/A (Nuevo)',
                            'despues' => $esActivo ? 'Activo' : 'Inactivo'
                        ],
                        'Especificaciones' => [
                            'antes'   => 'Sin RAM asignada',
                            'despues' => "{$ram->capacidad_gb}GB | {$ram->clock_mhz}Mhz | {$ram->tipo_chz}"
                        ]
                    ] 
                ]
            ]);
        }
    }

    public function updated(Ram $ram): void
    {
        if ($ram->isDirty()) {
            $cambios = [];
            $tipoFinal = 'Actualizacion';
            $mensajeFinal = 'Se actualizó información de la RAM';

            foreach ($ram->getDirty() as $atributo => $nuevoValor) {
                if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue;

                $valorAnterior = $ram->getOriginal($atributo);
                $campoLegible = "RAM -> " . Str::headline($atributo);

                if ($atributo === 'is_active') {
                    if ($valorAnterior == 1 && $nuevoValor == 0) {
                        $tipoFinal = 'inactivacion RAM';
                        $mensajeFinal = 'COMPONENTE INACTIVADO: Una memoria RAM ha sido desactivada.';
                    } elseif ($valorAnterior == 0 && $nuevoValor == 1) {
                        $tipoFinal = 'activacion RAM';
                        $mensajeFinal = 'COMPONENTE REACTIVADO: ¡La memoria RAM vuelve a estar operativa!';
                    }
                    $antesTexto = $valorAnterior ? 'Activo' : 'Inactivo';
                    $despuesTexto = $nuevoValor ? 'Activo' : 'Inactivo';
                } else {
                    $antesTexto = $valorAnterior ?? 'N/A';
                    $despuesTexto = $nuevoValor ?? 'N/A';
                }

                $cambios[$campoLegible] = [
                    'antes'   => $antesTexto,
                    'despues' => $despuesTexto
                ];
            }

            if (!empty($cambios)) {
                Historial_log::create([
                    'activo_id'         => $ram->equipo_id,
                    'usuario_accion_id' => Auth::id() ?? 1,
                    'tipo_registro'     => $tipoFinal,
                    'detalles_json'     => [
                        'mensaje'          => $mensajeFinal,
                        'color'            => 'info',
                        'usuario_asignado' => $ram->equipos->usuario->name ?? 'N/A',
                        'rol'              => $ram->equipos->usuario->rol ?? 'N/A',
                        'cambios'          => $cambios
                    ]
                ]);
            }
        }
    }

    public function deleting(Ram $ram): void
    {
        $equipoId = $ram->equipo_id; 
        $equipoPadre = \App\Models\Equipo::find($equipoId);

        if ($equipoPadre) {
            Historial_log::create([
                'activo_id'         => $equipoPadre->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => 'Eliminacion',
                'detalles_json'     => [
                    'mensaje'          => "COMPONENTE ELIMINADO: Se retiró una RAM del equipo",
                    'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                    'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Ram Retirada' => [
                            'antes'   => "{$ram->capacidad_gb}GB | {$ram->clock_mhz}Mhz | {$ram->tipo_chz}",
                            'despues' => 'ELIMINADO'
                        ]
                    ],
                    'respaldo' => $ram->toArray() 
                ]
            ]);
        } else {
            Log::warning("No se pudo crear log de eliminación: La RAM {$ram->id} no tiene un equipo asociado.");
        }
    }
}