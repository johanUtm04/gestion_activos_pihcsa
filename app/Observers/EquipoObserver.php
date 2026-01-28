<?php

namespace App\Observers;

use App\Models\Equipo;
use App\Models\Historial_log;
use Illuminate\Support\Facades\Auth;

class EquipoObserver
{
    protected $tiposMapeados = [
        'CREATED' => 'Creacion',
        'UPDATED' => 'Actualizacion',
        'DELETED' => 'Eliminacion',
    ];

    protected static $registrado = false;

    public function created(Equipo $equipo): void
    {
        $resumenHardware = [
            'Procesador'     => $equipo->procesadores->first()->marca ?? 'N/D',
            'RAM'            => ($equipo->rams->first()->capacidad ?? 'N/D') . ' ' . ($equipo->rams->first()->tipo_memoria ?? ''),
            'Almacenamiento' => ($equipo->discosDuros->first()->capacidad ?? 'N/D') . ' ' . ($equipo->discosDuros->first()->tipo_disco ?? ''),
            'Monitor'        => $equipo->monitores->first()->marca ?? 'N/D',
            'Periferico'     => $equipo->perifericos->first()->tipo ?? 'N/D',
        ];

        // dd($resumenHardware);

        $hardwareString = collect($resumenHardware)
        ->map(fn($v, $k) => "$k: $v")
        ->implode(' | ');

        if (self::$registrado) return;

        $equipo->refresh();
        $equipo->load(['marca', 'tipoActivo', 'usuario']);

        // Historial_log::create([
        //     'activo_id'         => $equipo->id,
        //     'usuario_accion_id' => Auth::id() ?? 1,
        //     'tipo_registro'     => $this->tiposMapeados['CREATED'],
        //     'detalles_json'     => [
        //         'mensaje' => 'Registro integral de nuevo activo y componentes.',
        //         'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
        //         'rol' => $equipo->usuario->rol ?? 'N/A',
        //         'cambios' => [
        //             'Usuario Asignado' => ['antes' => 'N/A', 'despues' => $equipo->usuario->name ?? 'No encontrado'],
        //             'Marca del Equipo' => ['antes' => 'N/A', 'despues' => $equipo->marca->nombre ?? 'No generado'],
        //             'Tipo de Activo'   => ['antes' => 'N/A', 'despues' => $equipo->tipoActivo?->nombre ?? 'No disponible'],
        //             'Serial'           => ['antes' => 'N/A', 'despues' => $equipo->serial ?? 'No registrado'],
        //             'Hardware Inicial'     => ['antes' => 'N/A', 'despues' => $hardwareString],
        //             'Sistema Operativo'=> ['antes' => 'N/A', 'despues' => str_replace('|', ' ', $equipo->sistema_operativo)],
        //             'Valor Inicial'    => [
        //                 'antes'   => 'N/A', 
        //                 'despues' => '$' . number_format($equipo->valor_inicial, 2)
        //             ],
        //             'Fecha de Adquisicion' => ['antes' => 'N/A', 'despues' => $equipo->fecha_adquisicion ?? 'No disponible'],
        //             'Vida Util estimada'   => ['antes' => 'N/A', 'despues' => ($equipo->vida_util_estimada ?? 0) . ' años'],
        //         ]
        //     ]
        // ]);

        self::$registrado = true;
    }

    public function updated(Equipo $equipo)
    {
        if (self::$registrado) return;

        if ($equipo->isDirty()) {
            $cambios = [];
            
            foreach ($equipo->getDirty() as $atributo => $nuevoValor) {
                if ($atributo === 'updated_at') continue;

                $valorAnterior = $equipo->getOriginal($atributo);
                $label = \Illuminate\Support\Str::headline($atributo);

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
                        'mensaje' => 'Actualización de especificaciones',
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