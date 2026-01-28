<?php

namespace App\Http\Controllers;

// --- IMPORTACI�N DE MODELOS ---
use App\Models\Equipo;
use App\Models\Ubicacion;
use App\Models\Historial_log;
use App\Models\User;
use App\Models\Marca;
use App\Models\TipoActivo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EquipoController extends Controller
{
    /**
     * Muestra el listado principal de activos.
     * Limpia sesiones previas del Wizard para evitar conflictos de datos.
     */
    public function index(Request $request)
    {
        session()->forget('wizard_equipo');

        //Oye Laravel, prep�rate para traerme equipos y tr�elos con todas sus piezas (RAM, Discos, etc.) de una vez
        $query = Equipo::with(['usuario', 'ubicacion', 'monitores', 'discosDuros', 'rams', 'perifericos', 'procesadores']);
        #"Viene Algo en Buscador -seccion-"
        if ($request->filled('seccion')) {
            # code...
            $busqueda = $request->seccion;
            $query->where(function($seccion) use ($busqueda) {
            $seccion->where('marca_equipo', 'LIKE', '%' . $busqueda . '%')
              ->orWhere('serial', 'LIKE', '%' . $busqueda . '%')
              ->orWhere('tipo_equipo', 'LIKE', '%' . $busqueda . '%');
            });
        }

        #Filtro por Ubicacion
        if ($request->filled('ubicacion_id')) {
        $query->where('ubicacion_id', $request->ubicacion_id);
        }

        #Filtro por Usuario
        if ($request->filled('usuario_id')) {
        $query->where('usuario_id', $request->usuario_id);
        }

        # Filtro por Tipo de Activo
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }

        # Filtro por Marca
        if ($request->filled('tipo_activo_id')) {
            $query->where('tipo_activo_id', $request->tipo_activo_id );
        }

        $equipos = $query->paginate(11)->appends($request->all());
        $ubicaciones = Ubicacion::all();
        $usuarios = User::all();
        $marcas = Marca::all();
        $tipos = TipoActivo::all();
        $categorias = [
        'Dispositivos de Usuario' => ['Laptop', 'Desktop', 'All-in-One', 'Tablet', 'Smartphone', 'Workstation'],
        'Infraestructura' => ['Servidor', 'Rack', 'Switch', 'Router', 'Access Point', 'Firewall', 'UPS'],
        'Perifericos' => ['Monitor', 'Impresora', 'Multifuncional', 'Escaner', 'Proyector', 'Camara'],
        ];

        return view('equipos.index', compact('equipos', 'ubicaciones', 'categorias', 'usuarios', 'marcas', 'tipos'));
    }

    /**
     * Valida e inicia el proceso de creaci�n (Wizard).
     * Genera un UUID �nico para trackear la sesi�n del equipo nuevo.
     */
public function store(Request $request)
{
    // 1. Validamos usando los nombres reales de tu vista (create.blade.php)
    $request->validate([
        'marca_id'           => 'required|integer|exists:marcas,id',
        'tipo_activo_id'     => 'required|integer|exists:tipo_activos,id',
        'sistema_operativo'  => 'required|string|max:35',
        'serial'             => 'nullable|string|max:255',
        'usuario_id'         => 'required|integer|exists:users,id',
        'ubicacion_id'       => 'nullable|integer|exists:ubicaciones,id',
        'valor_inicial'      => 'nullable|numeric|min:0',
        'fecha_adquisicion'  => 'required|date',
        'vida_util_estimada' => 'required|string|max:255',
    ]);

    // 2. Mapeamos los datos para la sesión
    $data = [
        'serial'             => $request->serial ?? 'INT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        'usuario_id'         => $request->usuario_id,
        'ubicacion_id'       => $request->ubicacion_id,
        'valor_inicial'      => $request->valor_inicial ?? 0,
        'fecha_adquisicion'  => $request->fecha_adquisicion,
        'vida_util_estimada' => $request->vida_util_estimada,
        'sistema_operativo'  => $request->sistema_operativo,
        'marca_id'          => $request->marca_id,       
        'tipo_activo_id'    => $request->tipo_activo_id,
    ];

// dd($data);
    $uuid = Str::uuid()->toString();

    session()->put('wizard_equipo.uuid', $uuid);
    session()->put('wizard_equipo.equipo', $data);

    return redirect()->route('equipos.wizard-ubicacion', $uuid)
    ;
}


    /**
     * Carga la vista de edicion con todas sus relaciones.
     * Se usa Eager Loading para evitar el problema de N+1 consultas.
     */
    public function edit(Equipo $equipo)
    {
        $equipo->load(['monitores', 'discosDuros', 'rams', 'perifericos', 'procesadores']);
        $usuarios    = User::all();
        $ubicaciones = Ubicacion::all();
        $marcas = Marca::all();
        $tiposActivo = TipoActivo::all();
        return view('equipos.edit', compact('equipo', 'usuarios', 'ubicaciones', 'marcas','tiposActivo'));
    }

    /**
     * Procesa la actualizaci�n masiva del equipo y sus componentes din�micos.
     */
public function update(Request $request, Equipo $equipo)
{
    // dd($request->all());
    // 1. Validar (Opcional pero recomendado para seguridad)
    $request->validate([
        'marca_id'          => 'required|exists:marcas,id',
        'tipo_activo_id'    => 'required|exists:tipo_activos,id',
        'usuario_id'        => 'required|exists:users,id',
        'ubicacion_id'      => 'nullable|exists:ubicaciones,id',
        'serial'            => 'required|string|max:255',
        'fecha_adquisicion' => 'required|date',
        'valor_inicial'     => 'nullable|numeric',
        'sistema_operativo' => 'nullable|string',
    ]);

    // 2. Preparar los datos base
    $data = $request->only([
        'serial', 'usuario_id', 
        'ubicacion_id', 'valor_inicial', 'fecha_adquisicion',
        'marca_id', 'tipo_activo_id',
    ]);

    $data = [
        'serial'            => $request->serial,
        'usuario_id'        => $request->usuario_id,
        'ubicacion_id'      => $request->ubicacion_id,
        'valor_inicial'     => $request->valor_inicial,
        'fecha_adquisicion' => $request->fecha_adquisicion,
        'marca_id'          => $request->marca_id,       
        'tipo_activo_id'    => $request->tipo_activo_id,  
    ];

    // dd($data);

    // L�gica para Vida �til (Concatenar n�mero + unidad)
    if ($request->filled('vida_util_estimada') && $request->filled('vida_util_unidad')) {
        $data['vida_util_estimada'] = $request->vida_util_estimada . ' ' . $request->vida_util_unidad;
    }

    // 3. Actualizar el equipo
    $equipo->update($data);

    // 4. Sincronizar relaciones din�micas
    $this->syncRelation($equipo->perifericos(),  $request->input('periferico', []));
    $this->syncRelation($equipo->rams(),         $request->input('ram', []));
    $this->syncRelation($equipo->procesadores(), $request->input('procesador', []));
    $this->syncRelation($equipo->monitores(),    $request->input('monitor', []));
    $this->syncRelation($equipo->discosDuros(),  $request->input('discoDuro', []));

    // Redirecci�n con c�lculo de p�gina
    $perPage = 11;
    $position = Equipo::where('id', '<=', $equipo->id)->count();
    $page = ceil($position / $perPage);

    return redirect()->route('equipos.index', ['page' => $page,
    'actualizado_id' => $equipo->id,
    'show_toast' => 1
    ])
    ->with('warning', 'Equipo actualizado correctamente')
    ->with('actualizado_id', $equipo->id);
}

    /**
     * Muestra el detalle completo de un activo.
     */
    public function show($id)
    {
        $equipo = Equipo::with(['usuario', 'ubicacion', 'monitores', 'discosDuros', 'rams', 'perifericos', 'procesadores'])
        ->findOrFail($id); 

        return view('equipos.detalles', compact('equipo'));
    }

    /**
     * Registro de mantenimiento en el historial (Log).
     */


    /**
     * Elimina el equipo y calcula la redirecci�n de p�gina.
     */
    public function destroy(Equipo $equipo)
    {
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page     = ceil($position / 11);
        
        $equipo->delete();

        return redirect()->route('equipos.index', ['page' => $page])
                         ->with('danger', 'Equipo eliminado correctamente');
    }

    // --- M�TODOS PRIVADOS DE L�GICA (HELPER FUNCTIONS) ---

    /**
     * Sincroniza una relaci�n HasMany: crea nuevos, actualiza existentes o elimina.
     */
    protected function syncRelation($relation, array $items)
    {
        foreach ($items as $item) {
            // 1. Verificar si el usuario marco para eliminar
            if (!empty($item['_delete'])) {
                if (!empty($item['id'])) {
                    // Si tiene ID, lo borramos fisicamente de la BD
                    $model = $relation->getRelated()->find($item['id']);
                    if ($model) {
                    $model->delete(); 
                }
                }
                // simplemente lo ignoramos y pasamos al siguiente.
                continue;
            }

            // 2. Preparar los datos (quitamos _delete para que no choque con la BD)
            $id = $item['id'] ?? null;

            //Logica Para historial
        //     if ($id) {
        //     $modelAnterior = $relation->getRelated()->find($id);
            
        //     if ($modelAnterior) {
        //         $nuevoEstado = isset($item['is_active']) ? 1 : 0;
                
        //         // Si el componente estaba activo y ahora se marca como inactivo
        //         if ($modelAnterior->is_active == 1 && $nuevoEstado == 0) {
        //             $nombreComponente = class_basename($relation->getRelated()); // Ej: Procesador, Ram...
                    
        //             Historial_log::create([
        //                 'activo_id'         => $modelAnterior->equipo_id,
        //                 'usuario_accion_id' => auth()->id(),
        //                 'tipo_registro'     => 'ESTADO_COMPONENTE',
        //                 'detalles_json'     => [
        //                     'mensaje' => "Componente Inactivado: $nombreComponente",
        //                     'cambios' => [
        //                         'Estado' => [
        //                             'antes'   => 'Activo',
        //                             'despues' => 'Inactivo'
        //                         ],
        //                         'Motivo' => [
        //                             'antes'   => 'N/A',
        //                             'despues' => $item['motivo_inactivo'] ?? 'No especificado'
        //                         ],
        //                         'Detalle' => [
        //                             'antes' => '-',
        //                             'despues' => ($item['marca'] ?? '') . " " . ($item['descripcion_tipo'] ?? $item['capacidad_gb'] ?? '')
        //                         ]
        //                     ]
        //                 ]
        //             ]);
        //         }
        //     }
        // }


            $data = collect($item)->forget(['id', '_delete'])->toArray();
            // $data['is_active'] = isset($item['is_active']) ? 1 : 0;
            // $data['motivo_inactivo'] = $item['motivo_inactivo'] ?? null;
            // 3. Actualizar o Crear
            // Laravel buscar� por ID, si lo halla actualiza, si es null crea.

            $data['is_active'] = isset($item['is_active']) ? 1 : 0; 
            $relation->updateOrCreate(['id' => $id], $data);
        }
    }

    private function isEmptyRecord($data) 
    {
        $filtered = collect($data)->except(['id', '_delete'])->filter();
        return $filtered->isEmpty();
    }


    public function indexaddwork (Equipo $equipo){
        $usuarios    = User::all();
        return view('equipos.addwork', compact('equipo', 'usuarios'));
    }



        public function saveWork (Equipo $equipo, Request $request)
    {
        $data = $request->validate([
            'tipo_evento'  => 'required|string',
            'tipo_evento_input' => 'required_if:tipo_evento,OTRO_VALOR|nullable|string|max:255',
            'usuario_id' => 'required|string',
            'fecha_evento' => 'required|date',
            'contexto'     => 'nullable|string',
            'costo'        => 'nullable|numeric',
        ]);

        $data = $request->only([
            'tipo_evento',
            'usuario_id',
            'fecha_evento',
            'contexto',
            'costo',
        ]);

        $data['tipo_evento'] =
        $request->tipo_evento === 'OTRO_VALOR'
        ? $request->tipo_evento_input
        : $request->tipo_evento;

        $usuarioMantenimiento = User::find($data['usuario_id']);
        $nombreUsuario = $usuarioMantenimiento->name;

            Historial_log::create([
                  'activo_id'         => $equipo->id,
                   'usuario_accion_id' => auth()->id(),
                      'tipo_registro'     => 'MANTENIMIENTO', 
                      'detalles_json' => [
                        'mensaje' => 'Nuevo Mantenimiento agregado',
                        'usuario_asignado' => $historial->name ?? 'conexion mal hecha we',
                        'rol' => $historial->rol ?? 'conexion mal hecha amor',
                        'cambios'          => [
                        'Detalles del Servicio' => [
                            'antes'   => 'N/A',
                            'despues' => "<div class='text-left'>" . 
                                "Tipo de Evento: {$data['tipo_evento']}<br>" .
                                "Usuario que realizó: {$nombreUsuario}<br>" .
                                "Fecha de Evento: {$data['fecha_evento']}<br>" .
                                "Contexto del Evento: " . ($data['contexto'] ?? 'N/A') . "<br>" .
                                "Costo: $" . ($data['costo'] ?? '0.00') .
                                "</div>"
                                ]  
                                    ]
                                        ]
                ]);


        $perPage = 11;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()->route('equipos.index', ['page' => $page])->with('secondary', 'Mantenimiento registrado')
        ->with('new_mantenimiento', $equipo->id);
    }


public function exportarGeneral()
{
    // 1. Obtener los datos de tu tabla
    $equipos = \App\Models\Equipo::with([
        'usuario', 
        'ubicacion',
        'monitores', 
        'discosDuros', 
        'rams', 
        'perifericos', 
        'procesadores'
    ])->get();
    

    $fileName = 'Reporte_General_PIHCSA_' . date('Y-m-d') . '.csv';

    // 2. Configurar cabeceras para que el navegador entienda que es un archivo
    $headers = [
        "Content-type"        => "text/csv; charset=UTF-8",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    // 3. Crear el archivo en memoria
    $callback = function() use($equipos) {
        $file = fopen('php://output', 'w');
        
        // A�adir BOM para que Excel reconozca tildes y e�es
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        //La coma es el separador
        fputs($file, "sep=,\n");

        // Encabezados basados en tu tabla
        fputcsv($file, [
        'ID',
        'Usuario',
        'Ubicacion',
        'Marca', 
        'Tipo', 
        'Serial', 
        'Procesador', 
        'Memoria RAM', 
        'Almacenamiento', 
        'Monitores', 
        'Perifericos'
        ]);

        foreach ($equipos as $equipo) {
        // Procesadores: Marca | Modelo/Descripción
        $procInfo = $equipo->procesadores->map(fn($p) => 
            "Marca: " . $p->marca . " | Modelo: " . $p->descripcion_tipo
        )->implode(' | ');

        // RAM: Capacidad | Velocidad | Tecnología
        $ramInfo = $equipo->rams->map(fn($r) => 
            "Capacidad: " . $r->capacidad_gb . " GB | Reloj: " . $r->clock_mhz . " MHz | Tipo: " . $r->tipo_chz
        )->implode(' | ');

        // Almacenamiento: Capacidad | Tipo | Interfaz
        $discoInfo = $equipo->discosDuros->map(fn($d) => 
            "Capacidad: " . $d->capacidad . " GB | Tipo: " . $d->tipo_hdd_ssd . " | Interfaz: " . $d->interface
        )->implode(' | ');

        // Monitores: Marca | S/N | Tamaño | Interfaz
        $monInfo = $equipo->monitores->map(fn($m) => 
            "Marca: " . $m->marca . " | Serial: " . ($m->serial ?? 'N/A') . " | Tamaño: " . $m->escala_pulgadas . "'' | Interfaz: " . $m->interface
        )->implode(' | ');

        // Periféricos: Tipo | Marca | S/N | Interfaz
        $perifInfo = $equipo->perifericos->map(fn($p) => 
            "Tipo: " . $p->tipo . " | Marca: " . $p->marca . " | Serial: " . ($p->serial ?? 'N/A') . " | Interfaz: " . $p->interface
        )->implode(' | ');

            fputcsv($file, [
                $equipo->id,
                $equipo->usuario ? $equipo->usuario->name : 'Sin asignar',
                $equipo->marca?->nombre,
                $equipo->ubicacion ? $equipo->ubicacion->nombre : 'Sin asignar',
                $equipo->tipoActivo?->nombre,
                $equipo->serial,
                $procInfo ?: 'N/A',
                $ramInfo ?: 'N/A',
                $discoInfo ?: 'N/A',
                $monInfo ?: 'N/A',
                $perifInfo ?: 'N/A',
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
