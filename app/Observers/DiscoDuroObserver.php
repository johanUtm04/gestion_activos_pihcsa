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
        'DISCODURO' => 'componente-extra',
    ];

    public function created(DiscoDuro $discoDuro): void
    {
        // IMPORTANTE: Usamos $discoDuro (nombre exacto del argumento)
        $equipo = $discoDuro->equipos; 

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => "{$this->tiposMapeados['DISCODURO']} Almacenamiento",
                'detalles_json'     => [
                    'mensaje'          => 'NUEVO COMPONENTE: Se instaló una unidad de almacenamiento',
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'rol'              => $equipo->usuario->rol ?? 'N/A',
                    'cambios' => [
                        'Disco Duro / SSD' => [
                            'antes'   => 'Inexistente',
                            'despues' => "Capacidad: {$discoDuro->capacidad} | Tipo: {$discoDuro->tipo_hdd_ssd} | Interface: {$discoDuro->interface}"
                        ]
                    ]
                ]
            ]);
        }
    }

    public function updated(DiscoDuro $discoDuro): void
    {
        if ($discoDuro->isDirty()) {
            $cambios = [];
            $tipoRegistro = $this->tiposMapeados['UPDATED'];

            foreach ($discoDuro->getDirty() as $atributo => $nuevoValor) {
                if (in_array($atributo, ['updated_at', 'equipo_id', 'created_at'])) continue;

                $campoLegible = "Almacenamiento -> " . Str::headline($atributo);
                
                // Detectar si el cambio fue inactivación
                if ($atributo === 'is_active' && $nuevoValor == 0) {
                    $tipoRegistro = 'inactivacion Almacenamiento';
                }

                $cambios[$campoLegible] = [
                    'antes'   => $discoDuro->getOriginal($atributo) ?: 'N/A',
                    'despues' => $nuevoValor
                ];
            }

            if (!empty($cambios)) {
                Historial_log::create([
                    'activo_id'         => $discoDuro->equipo_id,
                    'usuario_accion_id' => Auth::id() ?? 1,
                    'tipo_registro'     => $tipoRegistro,
                    'detalles_json'     => [
                        'mensaje'          => 'Se actualizó información técnica del disco',
                        'usuario_asignado' => $discoDuro->equipos->usuario->name ?? 'N/A',
                        'rol'              => $discoDuro->equipos->usuario->rol ?? 'N/A',
                        'cambios'          => $cambios
                    ]
                ]);
            }
        }
    }

    public function deleting(DiscoDuro $discoDuro): void
    {
        $equipoId = $discoDuro->equipo_id; 
        $equipoPadre = \App\Models\Equipo::find($equipoId);

        if ($equipoPadre) {
            Historial_log::create([
                'activo_id'         => $equipoPadre->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $this->tiposMapeados['DELETED'],
                'detalles_json'     => [
                    'mensaje'          => "COMPONENTE ELIMINADO: Se retiró un Disco Duro del equipo",
                    'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                    'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Disco Duro Retirado' => [
                            'antes'   => "Capacidad: {$discoDuro->capacidad} | Tipo: {$discoDuro->tipo_hdd_ssd} | Interface: {$discoDuro->interface}",
                            'despues' => 'ELIMINADO'
                        ]
                    ],
                    'respaldo' => $discoDuro->toArray() 
                ]
            ]);
        } else {
            Log::warning("No se pudo crear log de eliminación: El DiscoDuro {$discoDuro->id} no tiene un equipo asociado.");
        }
    }
}