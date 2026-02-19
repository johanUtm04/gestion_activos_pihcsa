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
        $discoDuro->is_active = true;
        $discoDuro->motivo_inactivo = null;
        $discoDuro->saveQuietly();

        $equipo = $discoDuro->equipos; 
        $esActivo = true;

        $tipoRegistro = 'componente-extra (Disco-Duro)';
        $mensaje = "SE AGREGÓ PERIFÉRICO: " . $discoDuro->capacidad . " " . $discoDuro->tipo_hdd_ssd;

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => $tipoRegistro,
                'detalles_json'     => [
                    'mensaje'          => $mensaje,
                    'usuario_asignado' => $discoDuro->equipos->usuario->name ?? 'N/A',
                    'rol'              => $discoDuro->equipos->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Estado Inicial' => [
                            'antes'   => 'N/A (Nuevo)',
                            'despues' => $esActivo ? ' Activo' : ' Inactivo'
                        ],
                        'Detalle' => [
                            'antes'   => '-',
                            'despues' => "Capacidad: {$discoDuro->capacidad} | Tipo: {$discoDuro->tipo_hdd_ssd} | Interfaz: {$discoDuro->interface}"
                        ]
                    ]
                ]
            ]);
        }
    }

    public function updated(DiscoDuro $discoDuro): void
    {

        if ($discoDuro->created_at && $discoDuro->created_at->diffInSeconds(now()) < 2) {
            return;
        }

        if ($discoDuro->isDirty()) {
            $cambios = [];
            $mensajeFinal = 'Se actualizó información de la ram';
            $tipoFinal = 'edicion-componente-extra (Disco-Duro)'; 

            foreach ($discoDuro->getDirty() as $atributo => $nuevoValor) {
                if (in_array($atributo, ['updated_at', 'equipo_id', 'created_at'])) continue;

                $valorAnterior = $discoDuro->getOriginal($atributo);
                $campoLegible = "Almacenamiento -> " . Str::headline($atributo);
                
                // Detectar si el cambio fue inactivación
                if ($atributo === 'is_active') {
                    if ($valorAnterior == 1 && $nuevoValor == 0) {
                        $tipoFinal = 'inactivacion Disco Duro';
                        $mensajeFinal = 'COMPONENTE INACTIVADO: El Disco Duro ha sido puesto fuera de servicio.';
                    } elseif ($valorAnterior == 0 && $nuevoValor == 1) {
                        $tipoFinal = 'activacion Disco Duro';
                        $mensajeFinal = 'COMPONENTE REACTIVADO: ¡El Disco Duro vuelve a estar operativo!';
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
                    'activo_id'         => $discoDuro->equipo_id,
                    'usuario_accion_id' => auth()->id() ?? 1,
                    'tipo_registro'     => $tipoFinal,
                    'detalles_json'     => [
                        'mensaje'          => $mensajeFinal,
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