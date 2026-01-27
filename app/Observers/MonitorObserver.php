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
        'MONITOR'     => 'Componente Extra',
    ];
public function created(Monitor $monitor): void
{
    // Obtenemos el equipo al que se le sumó el monitor
    $equipo = $monitor->equipos; 

    if ($equipo) {
        Historial_log::create([
            'activo_id'         => $equipo->id,
            'usuario_accion_id' => Auth::id() ?? 1,
            'tipo_registro'     => $this->tiposMapeados['MONITOR'], 
            'detalles_json'     => [
                'mensaje'          => 'NUEVO COMPONENTE: Se sumó un monitor',
                'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                'rol'              => $equipo->usuario->rol ?? 'N/A',
                'cambios' => [
                    'Monitor Adicional' => [
                        'antes'   => 'Inexistente',
                        'despues' => "Marca: {$monitor->marca} | S/N: {$monitor->serial} | Escala: {$monitor->escala_pulgadas}\" | Interface: {$monitor->interface}"
                    ]
                ] 
            ]
        ]);
    }
}

    /**
     * Handle the Monitor "updated" event.
     */
    public function updated(Monitor $monitor): void
    {
        //

        // 1. Verificamos si hubo cambios reales ignorando la fecha de actualización
        if ($monitor->isDirty()) {
            $cambios = [];

            foreach ($monitor->getDirty() as $atributo => $nuevoValor) {
                    if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue;

                    // 2. Creamos una etiqueta clara para el historial
                    $campoLegible = "Monitor -> " . Str::headline($atributo);

                    $cambios[$campoLegible] = [
                        'antes'   => $monitor->getOriginal($atributo),
                        'despues' => $nuevoValor
                    ];
                }

                // 3. Solo creamos el log si el array de cambios no quedó vacío
                if (!empty($cambios)) {
                    Historial_log::create([
                        'activo_id'         => $monitor->equipo_id, // Vinculamos al equipo padre
                        'usuario_accion_id' => Auth::id() ?? 1,
                        'tipo_registro'     => $this->tiposMapeados['UPDATED'],
                        'detalles_json'     => [
                            'mensaje'          => 'Se actualizó información del monitor',
                            'usuario_asignado' => $monitor->equipos->usuario->name ?? 'N/A',
                            'rol'              => $monitor->equipos->usuario->rol ?? 'N/A',
                            'cambios'          => $cambios
                        ]
                    ]);
                }}
        }

    /**
     * Handle the Monitor "deleted" event.
     */
    public function deleted(Monitor $monitor): void
    {
        //
    }

    public function deleting(Monitor $monitor): void
    {
        // 1.- Obtenemos el ID directamente de la columna, no de la relación es decir, $168 por ejemplo
        $equipoId = $monitor->equipo_id; 

        // 2. Buscamos el equipo de forma manual para asegurar que exista
        //es decir buscamos ese registro en la tabla
        $equipoPadre = \App\Models\Equipo::find($equipoId);

        //3.- Si la Tomamos de Buena Manera crearemos un registro en Historial_Log
        if ($equipoPadre) {
            Historial_log::create([
                'activo_id'         => $equipoPadre->id, // Vinculamos al ID del equipo
                'usuario_accion_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'tipo_registro'     => $this->tiposMapeados['DELETED'],
                'detalles_json'     => [
                    'mensaje'          => "COMPONENTE ELIMINADO: Se retiró un monitor del equipo",
                    'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                    'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Monitor Retirado' => [
                            'antes'   => "Marca: {$monitor->marca} | Serial: {$monitor->serial},
                            | Escala en Pulgadas: {$monitor->escala_pulgadas} | Interface: {$monitor->interface}",
                            'despues' => 'ELIMINADO'
                        ]
                    ],
                    'respaldo' => $monitor->toArray() 
                ]
            ]);
        } else {    //4.-En caso de Error
            Log::warning("No se pudo crear log de eliminación: El monitor {$monitor->id} no tiene un equipo asociado.");
        }
    }

    /**
     * Handle the Monitor "restored" event.
     */
    public function restored(Monitor $monitor): void
    {
        //
    }

    /**
     * Handle the Monitor "force deleted" event.
     */
    public function forceDeleted(Monitor $monitor): void
    {
        //
    }
}
