<?php

namespace App\Observers;

use App\Models\Equipo;
use App\Models\Historial_log;
use Illuminate\Support\Facades\Auth;

class EquipoObserver
{
//Arreglo que traduce los tipos de eventos en texto legible
protected $tiposMapeados = [
    'CREATED' => 'Creacion',
    'UPDATED' => 'Actualizacion',
    'DELETED' => 'Eliminacion',
];

//Bandera para evitar que el observer registre el mismo evento varias veces
protected static $registrado = false;
    public function created(Equipo $equipo): void
    {

    }

    public function updated(Equipo $equipo)
    {
        //inicializamos variables
        $antes   = null;
        $despues = null;

        
        //Si cambia algo del modelo 
        if (self::$registrado) return;

        $motivoPrincipal = ('Actualizacion de especificaciones');

        $camposIgnorar = ['updated_at', 'created_at'];

        if ($equipo->isDirty()) {
            $cambios = [];
            
            foreach ($equipo->getDirty() as $atributo => $nuevoValor) {
                if (in_array($atributo, $camposIgnorar)) continue;

                $valorAnterior = $equipo->getOriginal($atributo);
                $label = \Illuminate\Support\Str::headline($atributo);
                $tipoCampo = 'general';

                $mapeoHardware = [
                    'procesador' => ['label' => 'Procesador', 'color' => 'blue', 'icon' => 'fa-microchip'],
                    'ram'        => ['label' => 'RAM', 'color' => 'purple', 'icon' => 'fa-memory'],
                    'disco_duro' => ['label' => 'Disco Duro', 'color' => 'cyan', 'icon' => 'fa-hdd'],
                    'monitor'    => ['label' => 'Monitor', 'color' => 'indigo', 'icon' => 'fa-desktop'],
                    'periferico' => ['label' => 'Periférico', 'color' => 'teal', 'icon' => 'fa-keyboard'],
                ];

                if (array_key_exists($atributo, $mapeoHardware)) {
                    $label = $mapeoHardware[$atributo]['label'];
                    $tipoCampo = 'hardware';
                    $meta = $mapeoHardware[$atributo];
                }

                // --- Lógica de Mapeo de IDs a Nombres Reales ---
                if ($atributo === 'marca_id') {
                    $antes   = \App\Models\Marca::find($valorAnterior)?->nombre ?? 'Sin Marca';
                    $despues = \App\Models\Marca::find($nuevoValor)?->nombre ?? 'Sin Marca';
                    $label   = 'Marca';
                } 
                elseif ($atributo === 'tipo_activo_id') {
                    $antes   = \App\Models\TipoActivo::find($valorAnterior)?->nombre ?? 'Sin Tipo';
                    $despues = \App\Models\TipoActivo::find($nuevoValor)?->nombre ?? 'Sin Tipo';
                    $label   = 'Tipo de Equipo';
                }
                elseif ($atributo === 'usuario_id') {
                    $antes   = \App\Models\User::find($valorAnterior)?->name ?? 'Sin Asignar';
                    $despues = \App\Models\User::find($nuevoValor)?->name ?? 'Sin Asignar';
                    $label   = 'Usuario Custodio';
                }
                elseif ($atributo === 'ubicacion_id') {
                    $antes   = \App\Models\Ubicacion::find($valorAnterior)?->nombre ?? 'N/A';
                    $despues = \App\Models\Ubicacion::find($nuevoValor)?->nombre ?? 'N/A';
                    $label   = 'Ubicación';
                }
                elseif ($atributo === 'valor_inicial') {
                    $antes   = '$' . number_format($valorAnterior, 2);
                    $despues = '$' . number_format($nuevoValor, 2);
                }
                elseif ($atributo === 'vida_util_estimada') {
                    $antes   = $valorAnterior . ' años';
                    $despues = $nuevoValor . ' años';
                }
                else {
                    $antes   = $valorAnterior ?? 'N/A';
                    $despues = $nuevoValor ?? 'N/A';
                }

                $cambios[$label] = [
                    'antes'   => $antes,
                    'despues' => $despues
                ];
            }

            if (!empty($cambios)) {
                Historial_log::create([
                    'activo_id'         => $equipo->id,
                    'usuario_accion_id' => Auth::id() ?? 1,
                    'tipo_registro'     => $this->tiposMapeados['UPDATED'],
                    'detalles_json'     => [
                        'mensaje' => $motivoPrincipal,
                        'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                        'cambios' => $cambios
                    ]
                ]);
                self::$registrado = true;
            }
        }



    }

    public function deleting(Equipo $equipo)
    {
        Historial_log::create([
            'activo_id'         => $equipo->id,
            'usuario_accion_id' => Auth::id() ?? 1,
            'tipo_registro'     => $this->tiposMapeados['DELETED'],
            'detalles_json'     => [
                'mensaje' => 'ELIMINACIÓN DEFINITIVA: El activo ha sido removido.',
                'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                'cambios' => [
                    'Registro Eliminado' => [
                        'antes' => "Equipo: {$equipo->nombre_equipo} | S/N: {$equipo->serial}",
                        'despues' => 'BORRADO POR USUARIO'
                    ]
                ],
                'respaldo_total' => $equipo->toArray() 
            ]
        ]);
    }
}