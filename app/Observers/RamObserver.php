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
        $ram->is_active = true;
        $ram->motivo_inactivo = null;
        $ram->saveQuietly();

        $equipo = $ram->equipos; 
        $esActivo = $esActivo = true; 

        $tipoRegistro = $esActivo ? 'componente-extra (RAM)' : 'inactivacion RAM';

        $mensaje = "SE AGREGÓ PERIFÉRICO: " . $ram->capacidad_gb . " " . $ram->clock_mhz;

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $tipoRegistro, 
                'detalles_json'     => [
                    'mensaje'          => $mensaje,
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    'cambios' => [
                        'Estado Inicial' => [
                            'antes'   => 'N/A (Nuevo)',
                            'despues' => $esActivo ? 'Activo' : 'Inactivo'
                        ],
                        'Especificaciones' => [
                            'antes'   => '-',
                            'despues' => "Capacidad: {$ram->capacidad_gb}GB | Clock: {$ram->clock_mhz}Mhz | Tipo: {$ram->tipo_chz}"
                        ]
                    ] 
                ]
            ]);
        }
    }

    public function updated(Ram $ram): void
    {

        if ($ram->created_at && $ram->created_at->diffInSeconds(now()) < 2) {
            return;
        }

        if ($ram->isDirty()) {
            $cambios = [];
            $mensajeFinal = 'Se actualizó información de la RAM';
            $tipoFinal = 'edicion-componente-extra (RAM)'; 

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