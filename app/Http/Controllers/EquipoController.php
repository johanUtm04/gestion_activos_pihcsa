<?php

namespace App\Http\Controllers;

use App\Models\{
    Equipo,
    Ubicacion,
    Historial_log,
    User,
    Monitor,
    DiscoDuro,
    Ram,
    Procesador,
    Marca,
    TipoActivo,
};


use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Controlador Principal ...
|--------------------------------------------------------------------------
*/

class EquipoController extends Controller
{

    //Metodo para mostrar vista principal
    public function index(Request $request)
    {

        session()->forget('wizard_equipo');

        $equipos = Equipo::with(['usuario', 'ubicacion', 'marca', 'tipoActivo', 'procesadores', 'rams', 'discosDuros'])
        ->filtrar($request->all())
        ->orderBy('created_at', 'asc') 
        ->paginate(10)
        ->withQueryString();

        //Logica de Semaforo
        $equipos->getCollection()->transform(function ($equipo) {
            $equipo->semaforo = $this->calcularSemaforo($equipo);
        return $equipo;
        });

        //Consultas
        $ubicaciones = Ubicacion::all();
        $usuarios = User::all();
        $marcas = Marca::all();
        $tipos = TipoActivo::all();

        //Monitores
        $marcas_monitores = Monitor::distinct()->orderBy('marca', 'asc')->pluck('marca');
        $escalas_pulgadas = Monitor::distinct()->orderBy('escala_pulgadas', 'asc')->pluck('escala_pulgadas');
        $monitor_interface = Monitor::distinct()->orderBy('interface', 'asc')->pluck('interface');

        //Discos
        $discos_capacidades = DiscoDuro::distinct()->orderBy('capacidad', 'asc')->pluck('capacidad');
        $discos_tipos = DiscoDuro::distinct()->pluck('tipo_hdd_ssd'); 
        $discos_interfaces = DiscoDuro::distinct()->pluck('interface');

        //Rams
        $rams_capacidades = Ram::distinct()->orderBy('capacidad_gb', 'asc')->pluck('capacidad_gb');
        $rams_clocks = Ram::distinct()->orderBy('clock_mhz', 'asc')->pluck('clock_mhz');
        $rams_tipos = Ram::distinct()->orderBy('tipo_chz', 'asc')->pluck('tipo_chz');

        //Procesadores
        $procesador_marcas = Procesador::distinct()->orderBy('marca', 'asc')->pluck('marca');
        $procesador_tipos = Procesador::distinct()->orderBy('descripcion_tipo', 'asc')->pluck('descripcion_tipo');

        $categorias = [
            'Dispositivos de Usuario' => ['Laptop', 'Desktop', 'All-in-One', 'Tablet', 'Smartphone', 'Workstation'],
            'Infraestructura' => ['Servidor', 'Rack', 'Switch', 'Router', 'Access Point', 'Firewall', 'UPS'],
            'Perifericos' => ['Monitor', 'Impresora', 'Multifuncional', 'Escaner', 'Proyector', 'Camara'],
        ];

        return view('equipos.index', compact('equipos', 'ubicaciones', 'categorias', 'usuarios', 'marcas', 'tipos', 'marcas_monitores', 'escalas_pulgadas', 'monitor_interface',
        'discos_capacidades', 'discos_tipos', 'discos_interfaces',
        'rams_capacidades', 'rams_clocks', 'rams_tipos',
        'procesador_marcas', 'procesador_tipos'
        ));
    }

    //Metodo para crear registro en Base de datos
    public function store(Request $request)
    {
        $request->validate([
            'marca_id'           => 'required|integer|exists:marcas,id',
            'modelo'             => 'required|string|max:100',
            'tipo_activo_id'     => 'required|integer|exists:tipo_activos,id',
            'sistema_operativo'  => 'required|string|max:50',
            'serial'             => 'nullable|string|max:255',
            'usuario_id'         => 'required|integer|exists:users,id',
            'ubicacion_id'       => 'nullable|integer|exists:ubicaciones,id',
            'valor_inicial'      => 'nullable|numeric|min:0',
            'fecha_adquisicion'  => 'required|date',
            'vida_util_estimada' => 'required|string|max:255',
        ]);
        // Generación de serial más segura si es nulo
        $serialFinal = $request->serial 
            ? strtoupper($request->serial) 
        : 'INT-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        //Mapeo los datos para la sesión
        $data = [
            'serial'             => $serialFinal,
            'usuario_id'         => $request->usuario_id,
            'ubicacion_id'       => $request->ubicacion_id,
            'valor_inicial'      => $request->valor_inicial ?? 0,
            'fecha_adquisicion'  => $request->fecha_adquisicion,
            'vida_util_estimada' => $request->vida_util_estimada,
            'sistema_operativo'  => $request->sistema_operativo,
            'marca_id'          => $request->marca_id,    
            'modelo'         => $request->modelo,   
            'tipo_activo_id'    => $request->tipo_activo_id,
        ];

        $uuid = Str::uuid()->toString();

        session()->put('wizard_equipo.uuid', $uuid);
        session()->put('wizard_equipo.equipo', $data);

        return redirect()->route('equipos.wizard.ubicacion', $uuid);
    }


    //Metodo para cargar formulario de edicion
    public function edit(Equipo $equipo)
    {
        $equipo->load(['monitores', 'discosDuros', 'rams', 'perifericos', 'procesadores']);
        $usuarios    = User::all();
        $ubicaciones = Ubicacion::all();
        $marcas = Marca::all();
        $tiposActivo = TipoActivo::all();
        return view('equipos.edit', compact('equipo', 'usuarios', 'ubicaciones', 'marcas','tiposActivo'));
    }

    //Metodo para editar registro en Base de datos
    public function update(Request $request, Equipo $equipo)
    {
        $request->validate([
            'marca_id'          => 'required|exists:marcas,id',
            'modelo'            => 'nullable|string|max:100',
            'tipo_activo_id'    => 'required|exists:tipo_activos,id',
            'usuario_id'        => 'required|exists:users,id',
            'ubicacion_id'      => 'nullable|exists:ubicaciones,id',
            'serial'            => 'required|string|max:255',
            'fecha_adquisicion' => 'required|date',
            'valor_inicial'     => 'nullable|numeric',
            'sistema_operativo' => 'nullable|string',
        ]);

        $data = $request->only([
            'serial', 'usuario_id', 'modelo',
            'ubicacion_id', 'valor_inicial', 'fecha_adquisicion',
            'marca_id', 'tipo_activo_id',
        ]);

        $data = [
            'serial'            => $request->serial,
            'usuario_id'        => $request->usuario_id,
            'modelo'            => $request->modelo,
            'ubicacion_id'      => $request->ubicacion_id,
            'valor_inicial'     => $request->valor_inicial,
            'fecha_adquisicion' => $request->fecha_adquisicion,
            'marca_id'          => $request->marca_id,       
            'tipo_activo_id'    => $request->tipo_activo_id,  
        ];

        if ($request->filled('vida_util_estimada') && $request->filled('vida_util_unidad')) {
            $data['vida_util_estimada'] = $request->vida_util_estimada . ' ' . $request->vida_util_unidad;
        }

       $equipo->update($data);

        $this->syncRelation($equipo->perifericos(),  $request->input('periferico', []));
        $this->syncRelation($equipo->rams(),         $request->input('ram', []));
        $this->syncRelation($equipo->procesadores(), $request->input('procesador', []));
        $this->syncRelation($equipo->monitores(),    $request->input('monitor', []));
        $this->syncRelation($equipo->discosDuros(),  $request->input('discoDuro', []));

        $perPage = 10;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()->route('equipos.index', ['page' => $page])
        ->with('warning', 'Equipo actualizado')
        ->with('actualizado_id', $equipo->id)   ;
    }

    //Metodo paara ver un registro en especifico
    public function show($id)
    {
        $equipo = Equipo::with(['usuario', 'ubicacion', 'monitores', 'discosDuros', 'rams', 'perifericos', 'procesadores'])
        ->findOrFail($id); 

        return view('equipos.detalles', compact('equipo'));
    }

    //Metodo para eliminar registro de base de datos
    public function destroy(Request $request,Equipo $equipo)
    {

        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page     = ceil($position / 11);
        
        if (!$request->motivo || trim($request->motivo) === '') {
            return back()->with('error', 'El motivo de inactivación es obligatorio.');
        }

        $equipo->update([
            'motivo_inactivacion' => $request->motivo
        ]);

        $equipo->delete();

        return redirect()->route('equipos.index', ['page' => $page])
        ->with('danger', 'Equipo enviado a la papelera (Inactivado)
        <a href="' . route('equipos.index', ['filter' => 'inactivos']) . '" class="btn-papelera-alert">
        <i class="fas fa-trash-restore mr-1"></i> Ver Papelera
        </a>');
    }
    
    //Metodo Auxiliar para componentes extra
    protected function syncRelation($relation, array $items)
    {
        foreach ($items as $item) {
            if (!empty($item['_delete'])) {
                if (!empty($item['id'])) {
                    $model = $relation->getRelated()->find($item['id']);
                    if ($model) {
                    $model->delete(); 
                }
                }
                continue;
            }
            $id = $item['id'] ?? null;
            //Esta linea prepara los datos y deja de lado los que no se podran modificar creando un arreglo
            $data = collect($item)->forget(['id', '_delete'])->toArray();
            //Si esta activo es 1 si no es 0
            $data['is_active'] = isset($item['is_active']) ? 1 : 0; 

            //Tomamos su valor si lo da el usuario si no le damos uno
            $motivoActual = $item['motivo_inactivo'] ?? 'Sin motivo';

            //Si es 0(inactivo) y no tiene sello '|'
            if (!$data['is_active'] && strpos($motivoActual, '|') === false) {
                    //Le damos su sello
                $data['motivo_inactivo'] = $motivoActual . ' | ' . date('d/m/Y');
            } else {
                    //Si no se cumple algunas, si es 1 null queda el motivo caso contrario es 0, se deja tal cual
                $data['motivo_inactivo'] = $data['is_active'] ? null : $motivoActual;
                   }
            $relation->updateOrCreate(['id' => $id], $data);
        }
    }

    //...
    private function isEmptyRecord($data) 
    {
        $filtered = collect($data)->except(['id', '_delete'])->filter();
        return $filtered->isEmpty();
    }


    //Metodo para cargar vista
    public function indexaddwork (Equipo $equipo){
        $usuarios    = User::all();

        //Nos llevamos los resultado de la funcion de dias
        $semaforo = $this->calcularSemaforo($equipo);

        return view('equipos.addwork', compact('equipo', 'usuarios', 'semaforo'));
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

        $eventosQueResetan = [
            'Mantenimiento mensual'
        ];

        if (in_array($request->tipo_evento, $eventosQueResetan)) {
            $equipo->update([
                'fecha_ultimo_mantenimiento' => $request->fecha_evento
            ]);
        }

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
                    "{$data['tipo_evento']}<br>" .
                    "{$nombreUsuario}<br>" .
                    "{$data['fecha_evento']}<br>" .
                    ($data['contexto'] ?? 'N/A') . "<br>" .
                    "$" . ($data['costo'] ?? '0.00') .
                    "</div>"
            ]
        ]
        ]
        ]);

        $perPage = 10;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()->route('equipos.index', ['page' => $page])
        ->with('secondary', 'Mantenimiento registrado')
        ->with('new_mantenimiento', $equipo->id);
    }


public function exportarGeneral()
{
    // Optimización: Incluimos marca y tipoActivo para evitar consultas lentas
    $equipos = \App\Models\Equipo::with([
        'usuario', 
        'ubicacion',
        'marca',
        'tipoActivo',
        'monitores', 
        'discosDuros', 
        'rams', 
        'perifericos', 
        'procesadores'
    ])->get();

    $fileName = 'Reporte_Inventario_PIHCSA_' . date('Y-m-d') . '.csv';

    $headers = [
        "Content-type"        => "text/csv; charset=UTF-8",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function() use($equipos) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); 
        fputs($file, "sep=,\n");

        fputcsv($file, [
            'ID',
            'Usuario Responsable',
            'Ubicación / Depto',
            'Tipo de Activo', 
            'Marca', 
            'Modelo', 
            'Serial / Service Tag', 
            'Factura',
            'Sistema Operativo', 
            'Procesador (Detalle)', 
            'Memoria RAM (Total)', 
            'Almacenamiento', 
            'Monitores Asociados', 
            'Perifericos'
        ]);

        foreach ($equipos as $equipo) {
            // Simplificación de mapeos para legibilidad del CSV
            $procInfo = $equipo->procesadores->map(fn($p) => "{$p->marca} {$p->descripcion_tipo}")->implode(' | ');
            
            $ramInfo = $equipo->rams->map(fn($r) => "{$r->capacidad_gb}GB ({$r->tipo_chz} {$r->clock_mhz}MHz)")->implode(' | ');

            $discoInfo = $equipo->discosDuros->map(fn($d) => "{$d->capacidad}GB ({$d->tipo_hdd_ssd} - {$d->interface})")->implode(' | ');

            $monInfo = $equipo->monitores->map(fn($m) => "{$m->marca} {$m->escala_pulgadas}\" (S/N: " . ($m->serial ?? 'N/A') . ")")->implode(' | ');

            $perifInfo = $equipo->perifericos->map(fn($p) => "{$p->tipo}: {$p->marca} (" . ($p->serial ?? 'N/A') . ")")->implode(' | ');

            fputcsv($file, [
                $equipo->id,
                $equipo->usuario ? $equipo->usuario->name : 'Disponible en Stock',
                $equipo->ubicacion ? $equipo->ubicacion->nombre : 'N/A',
                $equipo->tipoActivo?->nombre ?? 'N/A',
                $equipo->marca?->nombre ?? 'N/A',
                $equipo->modelo ?? 'N/A', 
                $equipo->serial,
                $equipo->numero_factura ?? 'No asignada', 
                $equipo->sistema_operativo ?? 'N/A', 
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


    public function indexFactura(Equipo $equipo)
    {
        return view('equipos.factura', compact('equipo'));
    }


    public function saveFactura(Request $request, Equipo $equipo)
    {
        $request->validate([
            'numero_factura' => 'required|string|max:50|unique:equipos,numero_factura,' . $equipo->id,
        ]);

        $equipo->numero_factura = $request->input('numero_factura');
        $equipo->save();    

        $perPage = 10;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()->route('equipos.index', ['page' => $page])
        ->with('success', "<strong>Mantenimiento Registrado:</strong> Se agregó una nueva orden de trabajo para el activo {$equipo->serial}.")
        ->with('actualizado_factura', $equipo->id);
    }

    private function calcularSemaforo($equipo)
    {
        //Tomamos el id del tipo
        $tipo = $equipo->tipoActivo; 

        //Si esta vacio o menor o igual a 0
        if (!$tipo || $tipo->frecuencia_meses <= 0) {
            return (object) ['clase' => 'badge-secondary', 'texto' => 'N/A', 'icono' => 'fa-ban'];
        }

        //Condicion ternaria, para tomar la fecha, si no tomar una alternativa
        $fechaBase = $equipo->fecha_ultimo_mantenimiento 
            ? \Carbon\Carbon::parse($equipo->fecha_ultimo_mantenimiento) 
            : \Carbon\Carbon::parse($equipo->fecha_adquisicion ?? $equipo->created_at);

        //Creamos una copia, tomamos la fecha de hoy y remplazamos 
        $proximo = $fechaBase->copy()->addMonths($tipo->frecuencia_meses);
        $hoy = now()->startOfDay();
        $proximo = $proximo->startOfDay();
        
        $diasRestantes = (int) $hoy->diffInDays($proximo, false);

        // 1. Caso: Vencido (mayor que)
        if ($hoy->gt($proximo)) {
            $atraso = abs($diasRestantes);
            return (object) [
                'clase' => 'badge-danger', 
                'texto' => "VENCIDO HACE ({$atraso} d)", 
                'dias' => $diasRestantes,
                'icono' => 'fa-exclamation-triangle'
            ];
        }

        // 2. Caso: quedan pocos dias
        if ($diasRestantes <= 30) { 
            return (object) [
            'clase' => 'badge-warning', 
            'texto' => "OBLIGATORIO ({$diasRestantes} d)", 
            'dias'  => $diasRestantes,
            'icono' => 'fa-clock'
            ];
        }

        // 3. Caso: Al día 
        return (object) [
            'clase' => 'badge-success', 
            'texto' => 'EQUIPO AL DÍA', 
            'dias'  => $diasRestantes,
            'icono' => 'fa-check-circle'
        ];
    }

}
