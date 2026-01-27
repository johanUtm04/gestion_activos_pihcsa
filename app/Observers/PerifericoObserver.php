<?php

namespace App\Observers;

use App\Models\Periferico;
use App\Models\Historial_log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PerifericoObserver
{
    protected $tiposMapeados = [
        'CREATED' => 'Creacion',
        'UPDATED' => 'Actualizacion',
        'DELETED' => 'Eliminacion',
        'PERIFERICO'     => 'Componente Extra',
    ];
    public function created(Periferico $periferico): void
    {
        // Obtenemos el equipo al que se le sumó el monitor
        $equipo = $periferico->equipos; 

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $this->tiposMapeados['PERIFERICO'], 
                'detalles_json'     => [
                    'mensaje'          => 'NUEVO COMPONENTE: Se sumó un Periferico',
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    // Forzamos la estructura de cambios para que el Blade la pinte bonito
                    'cambios' => [
                        'Periferico Adicional' => [
                            'antes'   => 'Inexistente',
                            'despues' => "Tipo: {$periferico->tipo} | Marca: {$periferico->marca} | Serial: {$periferico->serial} | Interface: {$periferico->interface}"
                        ]
                    ]
                ]
            ]);
        }
    }

    /**
     * Handle the Periferico "updated" event.
     */
    public function updated(Periferico $periferico): void
    {
        //
        if ($periferico->isDirty()) {
            # code...
            $cambios = [];
            
            foreach($periferico->getDirty() as $atributo => $nuevovalor){
                if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue; 

                $campoLegible = "Periferico -> " . Str::headline($atributo);

                $cambios[$campoLegible] = [
                    'antes' => $periferico->getOriginal($atributo),
                    'despues' => $nuevovalor
                ];
            }

            // 3. Solo creamos el log si el array de cambios no quedó vacío
            if (!empty($cambios)) {
                Historial_log::create([
                    'activo_id'         => $periferico->equipo_id, // Vinculamos al equipo padre
                    'usuario_accion_id' => Auth::id() ?? 1,
                    'tipo_registro'     => $this->tiposMapeados['UPDATED'],
                    'detalles_json'     => [
                        'mensaje'          => 'Se actualizó información del periiferico',
                        'usuario_asignado' => $periferico->equipos->usuario->name ?? 'N/A',
                        'rol'              => $periferico->equipos->usuario->rol ?? 'N/A',
                        'cambios'          => $cambios
                    ]
                ]);
            }

        }
    }

    /**
     * Handle the Periferico "deleted" event.
     */
    public function deleted(Periferico $periferico): void
    {
        //
    }


    public function deleting(Periferico $periferico): void
    {
        // 1.- Obtenemos el ID directamente de la columna, no de la relación es decir, $168 por ejemplo
        $equipoId = $periferico->equipo_id; 

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
                    'mensaje'          => "COMPONENTE ELIMINADO: Se retiró un Periferico del equipo",
                    'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                    'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Periferico Retirado' => [
                            'antes'   => "Tipo: {$periferico->tipo} | Marca: {$periferico->marca} | Serial: {$periferico->serial}
                            | Interface: {$periferico->interface}",
                            'despues' => 'ELIMINADO'
                        ]
                    ],
                    'respaldo' => $periferico->toArray() 
                ]
            ]);
        } else {    //4.-En caso de Error
            Log::warning("No se pudo crear log de eliminación: El procesador {$periferico->id} no tiene un equipo asociado.");
        }
    }

    /**
     * Handle the Periferico "restored" event.
     */
    public function restored(Periferico $periferico): void
    {
        //
    }

    /**
     * Handle the Periferico "force deleted" event.
     */
    public function forceDeleted(Periferico $periferico): void
    {
        //
    }
}
