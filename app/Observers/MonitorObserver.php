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

    public function created(Monitor $monitor): void
    {
        $equipo = $monitor->equipos; 
        $esActivo = $monitor->is_active;

        // Estandarización para la vista
        $tipoRegistro = $esActivo ? 'componente-extra Monitor' : 'inactivacion Monitor';

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $tipoRegistro, 
                'detalles_json'     => [
                    'mensaje'          => "⚡ SE AGREGÓ MONITOR: {$monitor->marca} {$monitor->escala_pulgadas}\"",
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    'cambios' => [
                        'Estado Inicial' => [
                            'antes'   => 'N/A (Nuevo)',
                            'despues' => $esActivo ? 'Activo' : 'Inactivo'
                        ],
                        'Especificaciones' => [
                            'antes'   => 'Inexistente',
                            'despues' => "Marca: {$monitor->marca} | S/N: {$monitor->serial} | Escala: {$monitor->escala_pulgadas}\" | Interface: {$monitor->interface}"
                        ]
                    ] 
                ]
            ]);
        }
    }

    public function updated(Monitor $monitor): void
    {
        if ($monitor->isDirty()) {
            $cambios = [];
            $tipoFinal = 'Actualizacion';
            $mensajeFinal = 'Se actualizó información del monitor';

            foreach ($monitor->getDirty() as $atributo => $nuevoValor) {
                if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue;

                $valorAnterior = $monitor->getOriginal($atributo);
                $campoLegible = "Monitor -> " . Str::headline($atributo);

                // Lógica de activación/inactivación
                if ($atributo === 'is_active') {
                    if ($valorAnterior == 1 && $nuevoValor == 0) {
                        $tipoFinal = 'inactivacion Monitor';
                        $mensajeFinal = '⚠️ COMPONENTE INACTIVADO: El monitor ha sido puesto fuera de servicio.';
                    } elseif ($valorAnterior == 0 && $nuevoValor == 1) {
                        $tipoFinal = 'activacion Monitor';
                        $mensajeFinal = '✅ COMPONENTE REACTIVADO: ¡El monitor vuelve a estar operativo!';
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
                        'color'            => 'info',
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