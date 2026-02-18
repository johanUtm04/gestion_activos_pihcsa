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

/**
     * Método privado para mantener limpio el 'created'
     */
    private function armarResumenHardware(Equipo $equipo): string
    {
        $resumen = [
            'Procesador' => $equipo->procesadores->first() 
                ? collect([
                    $equipo->procesadores->first()->marca,
                    $equipo->procesadores->first()->descripcion_tipo ? "({$equipo->procesadores->first()->descripcion_tipo})" : null
                ])->filter()->implode(' ') 
                : 'N/A',

            'RAM' => $equipo->rams->first() 
                ? collect([
                    $equipo->rams->first()->capacidad_gb ? "{$equipo->rams->first()->capacidad_gb}GB" : null,
                    $equipo->rams->first()->tipo_chz,
                    $equipo->rams->first()->clock_mhz ? "{$equipo->rams->first()->clock_mhz}MHz" : null,
                    $equipo->rams->first()->serial ? "S/N: " . $equipo->rams->first()->serial : null
                ])->filter()->implode(' • ') 
                : 'N/A',

            'Disco' => $equipo->discosDuros->first() 
                ? collect([
                    $equipo->discosDuros->first()->capacidad,
                    $equipo->discosDuros->first()->tipo_hdd_ssd,
                    $equipo->discosDuros->first()->interface,
                    $equipo->discosDuros->first()->serial ? "S/N: {$equipo->discosDuros->first()->serial}" : null
                ])->filter()->implode(' • ') 
                : 'N/A',

            'Monitor' => $equipo->monitores->first() 
                ? collect([
                    $equipo->monitores->first()->marca,
                    $equipo->monitores->first()->escala_pulgadas ? "{$equipo->monitores->first()->escala_pulgadas}\"" : null,
                    $equipo->monitores->first()->interface,
                    $equipo->monitores->first()->serial ? "S/N: {$equipo->monitores->first()->serial}" : null
                ])->filter()->implode(' • ') 
                : 'N/A',

            'Periférico' => $equipo->perifericos->first() 
                ? collect([
                    $equipo->perifericos->first()->tipo,
                    $equipo->perifericos->first()->marca ? "({$equipo->perifericos->first()->marca})" : null,
                    $equipo->perifericos->first()->interface,
                    $equipo->perifericos->first()->serial ? "S/N: {$equipo->perifericos->first()->serial}" : null
                ])->filter()->implode(' • ') 
                : 'N/A',
        ];

        return collect($resumen)->map(fn($v, $k) => "**$k**: $v")->implode(' | ');
    }


    //👻Metodo que se ejecuta cuando se crea un equipo
    public function created(Equipo $equipo): void
    {
        // //Si ya se regirto algo antes salir
        // if (self::$registrado) return;
        // //refrescar el modelo
        // $equipo->refresh();
        // //Cargar Relaciones
        // $equipo->load(['marca', 'tipoActivo', 'usuario']);
        // //Marca como ejecutado
        // self::$registrado = true;

        // 1. Blind Spot: Validar que existan datos en la mochila.
        // Si creas un equipo por fuera del Wizard, no queremos que esto truene.
        if (!isset($equipo->datos_wizard)) {
            return;
        }

        $wizard = $equipo->datos_wizard;

        // Usamos withoutEvents para que los componentes no disparen sus propios observers individuales
        Equipo::withoutEvents(function () use ($equipo, $wizard) {
            if (!empty($wizard['monitor']))    $equipo->monitores()->create($wizard['monitor']);
            if (!empty($wizard['disco_duro'])) $equipo->discosDuros()->create($wizard['disco_duro']);
            if (!empty($wizard['ram']))        $equipo->rams()->create($wizard['ram']);
            if (!empty($wizard['periferico'])) $equipo->perifericos()->create($wizard['periferico']);
            if (!empty($wizard['procesador'])) $equipo->procesadores()->create($wizard['procesador']);
        });

        // Ahora que los componentes existen en la DB, los traemos al objeto $equipo
        $equipo->load(['procesadores', 'rams', 'discosDuros', 'monitores', 'perifericos', 'marca', 'tipoActivo', 'usuario']);

        //Arnar resumen hardaware
        $hardwareString = $this->armarResumenHardware($equipo);

        // 5. Crear el Historial Log de forma centralizada
        Historial_log::create([
            'activo_id'         => $equipo->id,
            'usuario_accion_id' => auth()->id() ?? 1,
            'tipo_registro'     => 'Creacion',
            'detalles_json'     => [
                'mensaje' => 'Registro integral de nuevo activo y componentes.',
                'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                'cambios' => [
                    'Hardware Inicial'     => ['antes' => 'N/A', 'despues' => $hardwareString],
                    'Sistema Operativo'    => ['antes' => 'N/A', 'despues' => $equipo->sistema_operativo],
                    'Valor Inicial'        => ['antes' => 'N/A', 'despues' => '$' . number_format($equipo->valor_inicial, 2)],
                    'Marca'                => ['antes' => 'N/A', 'despues' => $equipo->marca->nombre ?? 'N/A'],
                ]
            ]
        ]);
    }

    public function updated(Equipo $equipo)
    {

        // Verificamos si el campo numero_factura fue el que cambió
        if ($equipo->wasChanged('numero_factura')) {
            
            $antes = $equipo->getOriginal('numero_factura') ?? 'N/A';
            $despues = $equipo->numero_factura;

            \App\Models\Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => auth()->id() ?? 1,
                'tipo_registro'     => 'Actualizacion Factura',
                'detalles_json'     => [
                    'mensaje' => 'Se vinculó o modificó el número de factura del activo.',
                    'cambios' => [
                        'Numero de Factura' => [
                            'antes'   => $antes,
                            'despues' => $despues
                        ]
                    ]
                ]
            ]);
        };

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