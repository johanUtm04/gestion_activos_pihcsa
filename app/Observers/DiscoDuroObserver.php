<?php

namespace App\Observers;

use App\Models\DiscoDuro;
use App\Models\Historial_log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DiscoDuroObserver
{
    protected $tiposMapeados = [
        'CREATED' => 'Creacion',
        'UPDATED' => 'Actualizacion',
        'DELETED' => 'Eliminacion',
        'DISCODURO'     => 'Componente Extra',
    ];
public function created(DiscoDuro $disco): void
    {
        // Accedemos a la relación del equipo (ajusta según tu modelo)
        $equipo = $disco->equipos; 

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $this->tiposMapeados['DISCODURO'],
                'detalles_json'     => [
                    'mensaje'          => 'NUEVO COMPONENTE: Se instaló una unidad de almacenamiento',
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    'cambios' => [
                        'Disco Duro / SSD' => [
                            'antes'   => 'Inexistente',
                            'despues' => "Capacidad: {$disco->capacidad} | Tipo: {$disco->tipo_hdd_ssd} | Interface: {$disco->interface}"
                        ]
                    ]
                ]
            ]);
        }
    }

    /**
     * Handle the DiscoDuro "updated" event.
     */
public function updated($disco): void
    {
        if ($disco->isDirty()) {
            $cambios = [];

            foreach ($disco->getDirty() as $atributo => $nuevoValor) {
                // Ignorar campos de control
                if (in_array($atributo, ['updated_at', 'equipo_id', 'created_at'])) continue;

                $campoLegible = "Almacenamiento -> " . Str::headline($atributo);

                $cambios[$campoLegible] = [
                    'antes'   => $disco->getOriginal($atributo) ?: 'N/A',
                    'despues' => $nuevoValor
                ];
            }

            if (!empty($cambios)) {
                Historial_log::create([
                    'activo_id'         => $disco->equipo_id,
                    'usuario_accion_id' => Auth::id() ?? 1,
                    'tipo_registro'     => $this->tiposMapeados['UPDATED'],
                    'detalles_json'     => [
                        'mensaje'          => 'Se actualizó información técnica del disco',
                        'usuario_asignado' => $disco->equipo->usuario->name ?? 'N/A',
                        'rol'              => $disco->equipo->usuario->rol ?? 'N/A',
                        'cambios'          => $cambios
                    ]
                ]);
            }
        }
    }

    /**
     * Handle the DiscoDuro "deleted" event.
     */
    public function deleted(DiscoDuro $discoDuro): void
    {
        //
    }

    public function deleting(DiscoDuro $disco): void
    {
        // 1.- Obtenemos el ID directamente de la columna, no de la relación es decir, $168 por ejemplo
        $equipoId = $disco->equipo_id; 

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
                    'mensaje'          => "COMPONENTE ELIMINADO: Se retiró un Disco Duro del equipo",
                    'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                    'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Disco Duro Retirado' => [
                            'antes'   => "Marca: {$disco->capacidad} | Tipo de HDD SSD: {$disco->tipo_hdd_ssd}
                            | Interface: {$disco->interface}",
                            'despues' => 'ELIMINADO'
                        ]
                    ],
                    'respaldo' => $disco->toArray() 
                ]
            ]);
        } else {    //4.-En caso de Error
            Log::warning("No se pudo crear log de eliminación: El DiscoDuro {$disco->id} no tiene un equipo asociado.");
        }
    }

    /**
     * Handle the DiscoDuro "restored" event.
     */
    public function restored(DiscoDuro $discoDuro): void
    {
        //
    }

    /**
     * Handle the DiscoDuro "force deleted" event.
     */
    public function forceDeleted(DiscoDuro $discoDuro): void
    {
        //
    }
}
