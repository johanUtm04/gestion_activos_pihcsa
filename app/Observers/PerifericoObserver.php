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
        'CREATED'    => 'Creacion',
        'UPDATED'    => 'Actualizacion',
        'DELETED'    => 'Eliminacion',
        'PERIFERICO' => 'componente-extra',
    ];

    public function created(Periferico $periferico): void
    {
        $equipo = $periferico->equipos; 
        $esActivo = $periferico->is_active;

        // Usamos el sufijo para identificar el componente en el log
        $tipoRegistro = $esActivo ? 'componente-extra Periferico' : 'inactivacion Periferico';
        
        $mensaje = $esActivo 
            ? "⚡ SE AGREGÓ PERIFÉRICO: " . $periferico->tipo . " " . $periferico->marca 
            : "⚠️ PERIFÉRICO INSTALADO INACTIVO: " . $periferico->marca;

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
                            'despues' => "Tipo: {$periferico->tipo} | Marca: {$periferico->marca} | Interfaz: {$periferico->interface}"
                        ]
                    ]
                ]
            ]);
        }
    }

    public function updated(Periferico $periferico): void
    {
        if ($periferico->isDirty()) {
            $cambios = [];
            $mensajeFinal = 'Se actualizó información del periférico';
            $tipoFinal = 'Actualizacion'; // Default

            foreach($periferico->getDirty() as $atributo => $nuevoValor){
                if ($atributo === 'updated_at' || $atributo === 'equipo_id') continue; 

                $valorAnterior = $periferico->getOriginal($atributo);
                $campoLegible = "Periférico -> " . Str::headline($atributo);

                if ($atributo === 'is_active') {
                    if ($valorAnterior == 1 && $nuevoValor == 0) {
                        $tipoFinal = 'inactivacion Periferico';
                        $mensajeFinal = 'COMPONENTE INACTIVADO: El periférico ha sido puesto fuera de servicio.';
                    } elseif ($valorAnterior == 0 && $nuevoValor == 1) {
                        $tipoFinal = 'activacion Periferico';
                        $mensajeFinal = 'COMPONENTE REACTIVADO: ¡El periférico vuelve a estar operativo!';
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
                    'tipo_registro'     => $tipoFinal,
                    'detalles_json'     => [
                        'mensaje'          => $mensajeFinal,
                        'color'            => 'info',
                        'usuario_asignado' => $periferico->equipos->usuario->name ?? 'N/A',
                        'rol'              => $periferico->equipos->usuario->rol ?? 'N/A',
                        'cambios'          => $cambios
                    ]
                ]);
            }
        }
    }

    public function deleting(Periferico $periferico): void
    {
        $equipoId = $periferico->equipo_id; 
        $equipoPadre = \App\Models\Equipo::find($equipoId);

        if ($equipoPadre) {
            Historial_log::create([
                'activo_id'         => $equipoPadre->id,
                'usuario_accion_id' => Auth::id() ?? 1,
                'tipo_registro'     => 'Eliminacion',
                'detalles_json'     => [
                    'mensaje'          => "COMPONENTE ELIMINADO: Se retiró un periférico ({$periferico->tipo}) del equipo",
                    'usuario_asignado' => $equipoPadre->usuario->name ?? 'N/A',
                    'rol'              => $equipoPadre->usuario->rol ?? 'N/A',
                    'cambios'          => [
                        'Periférico Retirado' => [
                            'antes'   => "Tipo: {$periferico->tipo} | Marca: {$periferico->marca} | Serial: {$periferico->serial}",
                            'despues' => 'ELIMINADO'
                        ]
                    ],
                    'respaldo' => $periferico->toArray() 
                ]
            ]);
        } else {
            Log::warning("No se pudo crear log de eliminación: Periférico {$periferico->id} sin equipo.");
        }
    }
}