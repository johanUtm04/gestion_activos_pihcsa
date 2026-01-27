<?php

namespace App\Observers;

use App\Models\Historial_log;
use Illuminate\Support\Facades\Auth;
class MantenimientoObserver
{
    //
    public function created (Historial_log $Historial_log){

        //Obtenemos el Id del mantenimiento que se acaba de crear
        $historial = $Historial_log->actvo_id;

        if ($historial) {
            # code...
                Historial_log::create([
                    'activo_id'=> $historial->id,
                    'usuario_accion_id' => Auth::id() ?? 1,
                      'tipo_registro'     => 'MANTENIMIENTO', 
                      'detalles_json' => [
                        'mensaje' => 'Nuevo Mantenimiento agregado',
                        'usuario_asignado' => $historial->name ?? 'conexion mal hecha we',
                        'rol' => $historial->rol ?? 'conexion mal hecha amor',
                        'cambios'          => [
                            'Monitor Adicional' => [
                                'antes'   => 'Inexistente',
                                'despues' => "<ul class='list-unstyled mb-0'>" .
                                "<li><b>Marca:</b> {$historial->tipo_evento}</li>" .
                                "<li><b>S/N:</b> {$historial->costo}</li>" .
                                "<li><b>Interface:</b> {$historial->contexto}</li>" .
                                "</ul>"                    
                                ]
                        ]   
                      ]
                ]);
            }
    }
}
