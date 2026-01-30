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
        'PERIFERICO'     => 'Componente Extra -Vengo de El observer',
    ];
    public function created(Periferico $periferico): void
    {
        $equipo = $periferico->equipos; 
        $esActivo = $periferico->is_active;

        $tipoRegistro = $esActivo ? 'PERIFERICO' : 'INACTIVACION';
        $mensaje = $esActivo 
        ? " Nuevo componente instalado y operativo: " . $periferico->marca 
        : " Componente instalado pero fuera de servicio: " . $periferico->marca;

        if ($equipo) {
            Historial_log::create([
                'activo_id'         => $periferico->equipo_id,
                'usuario_accion_id' => auth()->id() ?? 1,
                'tipo_registro'     => $tipoRegistro,
                'detalles_json'     => [
                    'mensaje'          => $mensaje,
                    'usuario_asignado' => $periferico->equipos->usuario->name ?? 'N/A',
                    'rol'              => $periferico->equipos->usuario->rol ?? 'N/A',

                    'cambios'          => [
                        'Estado Inicial' => [
                            'antes'   => 'N/A (Nuevo)',
                            'despues' => $esActivo ? ' Activo' : ' Inactivo'
                        ],
                        'Motivo' => [
                            'antes'   => '-',
                            'despues' => $periferico->motivo_inactivo ?? 'Instalación inicial'
                        ],
                        'Detalle' => [
                            'antes'   => '-',
                            'despues' => $periferico->marca . " " . $periferico->interface
                        ]
                    ]
                ]
            ]);
        }
    }


    public function updated(Periferico $periferico): void
    {
        //
        if ($periferico->isDirty()) {
        $cambios = [];
        $esEstado = false;
        $mensajeFinal = 'Se actualizó información del periferico';
            
            foreach($periferico->getDirty() as $atributo => $nuevoValor){
                if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue; 

                $valorAnterior = $periferico->getOriginal($atributo);
                $campoLegible = "Periferico -> " . Str::headline($atributo);
                $colorFinal = 'info';

                if ($atributo === 'is_active') {
                    $esEstado = true;
                    if ($valorAnterior == 1 && $nuevoValor == 0) {
                        $tipoFinal = 'INACTIVACION PERIFERICO';
                        $mensajeFinal = 'COMPONENTE INACTIVADO: El periferico ha sido puesto fuera de servicio.';
                    } elseif ($valorAnterior == 0 && $nuevoValor == 1) {
                        $tipoFinal = 'ACTIVACION PERIFERICO';
                        $mensajeFinal = 'COMPONENTE REACTIVADO: ¡El periferico vuelve a estar operativo!';
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
                'activo_id'         => $periferico->equipo_id,
                'usuario_accion_id' => auth()->id() ?? 1,
                'tipo_registro' => $tipoFinal ?? 'Actualizacion',
                'detalles_json'     => [
                    'mensaje'          => $mensajeFinal,
                    'color'   => $colorFinal,
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
            Log::warning("No se pudo crear log de eliminación: El periferico {$periferico->id} no tiene un equipo asociado.");
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
