<?php

namespace App\Observers;

use App\Models\Procesador;
use App\Models\Historial_log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProcesadorObserver
{
    protected $tiposMapeados = [
        'CREATED' => 'Creacion',
        'UPDATED' => 'Actualizacion',
        'DELETED' => 'Eliminacion',
        'PROCESADOR'     => 'Componente Extra',
    ];
    public function created(Procesador $procesador): void
    {
        // Obtenemos el equipo al que se le sumó el procesador
        $equipo = $procesador->equipos; 

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $this->tiposMapeados['PROCESADOR'], 
                'detalles_json'     => [
                    'mensaje'          => 'NUEVO COMPONENTE: Se sumó un procesador',
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    'cambios' => [
                        'Procesador Nuevo' => [
                            'antes'   => 'Inexistente',
                            'despues' => "Marca: {$procesador->marca} | Modelo: {$procesador->descripcion_tipo}"
                        ]
                    ]
                ]
            ]);
        }
    }

    /**
     * Handle the Procesador "updated" event.
     */
public function updated(Procesador $procesador): void
{
    if ($procesador->isDirty()) {
        $cambios = [];
        $tipoFinal = $this->tiposMapeados['UPDATED']; // Por defecto 'Actualizacion'
        $mensajeFinal = 'Se actualizó información del procesador';

        foreach ($procesador->getDirty() as $atributo => $nuevoValor) {
            if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue;

            $valorAnterior = $procesador->getOriginal($atributo);
            $campoLegible = "Procesador -> " . Str::headline($atributo);

            // --- LÓGICA DE DETECCIÓN DE ESTADO ---
            if ($atributo === 'is_active') {
                if ($valorAnterior == 1 && $nuevoValor == 0) {
                    $tipoFinal = 'ESTADO_COMPONENTE'; // O "INACTIVACION" según prefieras en tu BD
                    $mensajeFinal = 'COMPONENTE INACTIVADO: El procesador ha sido puesto fuera de servicio.';
                } elseif ($valorAnterior == 0 && $nuevoValor == 1) {
                    $tipoFinal = 'ESTADO_COMPONENTE';
                    $mensajeFinal = 'COMPONENTE REACTIVADO: El procesador vuelve a estar operativo.';
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
                'activo_id'         => $procesador->equipo_id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $tipoFinal,
                'detalles_json'     => [
                    'mensaje'          => $mensajeFinal,
                    'usuario_asignado' => $procesador->equipos->usuario->name ?? 'N/A',
                    'rol'              => $procesador->equipos->usuario->rol ?? 'N/A',
                    'cambios'          => $cambios
                ]
            ]);
        }
    }
}

    /**
     * Handle the Procesador "deleted" event.
     */
public function deleting(Procesador $procesador): void
{
    // 1.- Obtenemos el ID directamente de la columna, no de la relación es decir, $168 por ejemplo
    $equipoId = $procesador->equipo_id; 

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
                'mensaje'          => "COMPONENTE ELIMINADO: Se retiró un procesador del equipo",
                'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                'cambios'          => [
                    'Procesador Retirado' => [
                        'antes'   => "Marca: {$procesador->marca} | Desc: {$procesador->descripcion_tipo}",
                        'despues' => 'ELIMINADO'
                    ]
                ],
                'respaldo' => $procesador->toArray() 
            ]
        ]);
    } else {    //4.-En caso de Error
        Log::warning("No se pudo crear log de eliminación: El procesador {$procesador->id} no tiene un equipo asociado.");
    }
}

    /**
     * Handle the Procesador "restored" event.
     */
    public function restored(Procesador $procesador): void
    {
        //
    }

    /**
     * Handle the Procesador "force deleted" event.
     */
    public function forceDeleted(Procesador $procesador): void
    {
        //
    }
}
