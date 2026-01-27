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
        'RAM'     => 'Componente Extra',
    ];
public function created(Ram $ram): void
    {
        // Obtenemos el equipo al que se le sumó a la ram
        $equipo = $ram->equipos; 

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $this->tiposMapeados['RAM'], 
                'detalles_json'     => [
                    'mensaje'          => 'NUEVO COMPONENTE: Se sumo una Ram',
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    'cambios' => [
                        'Ram Adicional' => [
                            'antes'   => 'Sin RAM as    ignada',
                            'despues' => "{$ram->capacidad_gb}GB | {$ram->clock_mhz}Mhz | {$ram->tipo_chz}"
                        ]
                    ] 
                ]
            ]);
        }
    }

    /**
     * Handle the Ram "updated" event.
     */
public function updated(Ram $ram): void
    {
        // 1. Verificamos si hubo cambios reales ignorando la fecha de actualización
        if ($ram->isDirty()) {
            $cambios = [];

            foreach ($ram->getDirty() as $atributo => $nuevoValor) {
                    if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue;

                    // 2. Creamos una etiqueta clara para el historial
                    $campoLegible = "Ram -> " . Str::headline($atributo);

                    $cambios[$campoLegible] = [
                        'antes'   => $ram->getOriginal($atributo),
                        'despues' => $nuevoValor
                    ];
                }

                // 3. Solo creamos el log si el array de cambios no quedó vacío
                if (!empty($cambios)) {
                    Historial_log::create([
                        'activo_id'         => $ram->equipo_id, // Vinculamos al equipo padre
                        'usuario_accion_id' => Auth::id() ?? 1,
                        'tipo_registro' => $this->tiposMapeados['UPDATED'],
                        'detalles_json'     => [
                            'mensaje'          => 'Se actualizó información del ram',
                            'usuario_asignado' => $ram->equipos->usuario->name ?? 'N/A',
                            'rol'              => $ram->equipos->usuario->rol ?? 'N/A',
                            'cambios'          => $cambios
                        ]
                    ]);
                }}
        }

    /**
     * Handle the Ram "deleted" event.
     */
    public function deleted(Ram $ram): void
    {
        //
    }


public function deleting(Ram $ram): void
{
    // 1.- Obtenemos el ID directamente de la columna, no de la relación es decir, $168 por ejemplo
    $equipoId = $ram->equipo_id; 

    // 2. Buscamos el equipo de forma manual para asegurar que exista
    //es decir buscamos ese registro en la tabla
    $equipoPadre = \App\Models\Equipo::find($equipoId);

    //3.- Si la Tomamos de Buena Manera crearemos un registro en Historial_Log
    if ($equipoPadre) {
        Historial_log::create([
            'activo_id'         => $equipoPadre->id, // Vinculamos al ID del equipo
            'usuario_accion_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'tipo_registro' => $this->tiposMapeados['DELETED'],
            'detalles_json'     => [
                'mensaje'          => "COMPONENTE ELIMINADO: Se retiró una Ram del equipo",
                'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                'cambios'          => [
                    'Ram Retirada' => [
                        'antes'   => "Capacidad en GB: {$ram->capacidad_gb} | Clock Mhz: {$ram->clock_mhz}
                        | Tipo de CHZ: {$ram->tipo_chz}",
                        'despues' => 'ELIMINADO'
                    ]
                ],
                'respaldo' => $ram->toArray() 
            ]
        ]);
    } else {    //4.-En caso de Error
        Log::warning("No se pudo crear log de eliminación: El procesador {$ram->id} no tiene un equipo asociado.");
    }
}

    /**
     * Handle the Ram "restored" event.
     */
    public function restored(Ram $ram): void
    {
        //
    }

    /**
     * Handle the Ram "force deleted" event.
     */
    public function forceDeleted(Ram $ram): void
    {
        //
    }
}
