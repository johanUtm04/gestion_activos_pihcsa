<?php

namespace App\Observers;

use App\Models\Equipo;
use App\Models\Monitor;
use App\Models\Historial_log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MonitorObserver
{
    protected $tiposMapeados = [
        'CREATED' => 'Creacion',
        'UPDATED' => 'Actualizacion',
        'DELETED' => 'Eliminacion',
        'MONITOR' => 'componente-extra',
    ];

    public function creating(Monitor $monitor): void
    {
        $monitor->is_active = true;
        $monitor->motivo_inactivo = null;
    }
    
    public function created(Monitor $monitor): void
    {
        $monitor->is_active = true;
        $monitor->motivo_inactivo = null;
        $monitor->saveQuietly();

        $equipo = $monitor->equipos; 
        $esActivo = true; 

        // Estandarización para la vista
        $tipoRegistro = 'componente-extra (Monitor)';
        $mensaje = "SE AGREGÓ PERIFÉRICO: " . $monitor->marca . " " . $monitor->escala_pulgadas;

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
                            'antes'   => 'Inexistente',
                            'despues' => "Marca: {$monitor->marca} | Escala: {$monitor->escala_pulgadas}\" | Interface: {$monitor->interface}"
                        ]
                    ] 
                ]
            ]);
        }
    }

    public function updated(Monitor $monitor): void
    {
        if ($monitor->created_at && $monitor->created_at->diffInSeconds(now()) < 2) {
            return;
        }
        if ($monitor->isDirty()) {
            $cambios = [];
            $tipoFinal = 'edicion-componente-extra (Monitor)';
            $mensajeFinal = 'Se actualizó información del monitor';

            foreach ($monitor->getDirty() as $atributo => $nuevoValor) {
                if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue;

                $valorAnterior = $monitor->getOriginal($atributo);
                $campoLegible = "Monitor -> " . Str::headline($atributo);

                if ($atributo === 'is_active') {
                    if ($valorAnterior == 1 && $nuevoValor == 0) {
                        $tipoFinal = 'inactivacion Periferico';
                        $mensajeFinal = 'COMPONENTE INACTIVADO: La Ram ha sido puesta fuera de servicio.';
                    } elseif ($valorAnterior == 0 && $nuevoValor == 1) {
                        $tipoFinal = 'activacion Periferico';
                        $mensajeFinal = 'COMPONENTE REACTIVADO: La Ram vuelve a estar operativa!';
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
                    'activo_id'         => $monitor->equipo_id,
                    'usuario_accion_id' => Auth::id() ?? 1,
                    'tipo_registro'     => $tipoFinal,
                    'detalles_json'     => [
                        'mensaje'          => $mensajeFinal,
                        'usuario_asignado' => $monitor->equipos->usuario->name ?? 'N/A',
                        'rol'              => $monitor->equipos->usuario->rol ?? 'N/A',
                        'cambios'          => $cambios
                    ]
                ]);
            }
        }
    }

    public function deleting(Monitor $monitor): void
    {
        $equipoId = $monitor->equipo_id; 
        $equipoPadre = \App\Models\Equipo::find($equipoId);

        if ($equipoPadre) {
            Historial_log::create([
                'activo_id'         => $equipoPadre->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => 'Eliminacion',
                'detalles_json'     => [
                    'mensaje'          => "🗑️ COMPONENTE ELIMINADO: Se retiró un monitor del equipo",
                    'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                    'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Monitor Retirado' => [
                            'antes'   => "{$monitor->marca} | Serial: {$monitor->serial} | {$monitor->escala_pulgadas}\"",
                            'despues' => 'ELIMINADO'
                        ]
                    ],
                    'respaldo' => $monitor->toArray() 
                ]
            ]);
        } else {
            Log::warning("No se pudo crear log de eliminación: Monitor {$monitor->id} sin equipo asociado.");
        }
    }
}