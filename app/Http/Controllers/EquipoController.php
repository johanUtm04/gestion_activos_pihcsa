<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Ubicacion;
use App\Models\Historial_log;
use App\Models\User;
use App\Models\Marca;
use App\Models\TipoActivo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

        $query = Equipo::query(); 
        session()->forget('wizard_equipo');

        $query = Equipo::with(['usuario', 'ubicacion', 'monitores', 'discosDuros', 'rams', 'perifericos', 'procesadores']);
        if ($request->filled('seccion')) {
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

        if ($request->filter == 'inactivos') {
            $query->onlyTrashed(); 
        } 

        $equipos = $query->with(['marca', 'tipoActivo', 'usuario', 'ubicacion'])
        ->orderBy('created_at', 'asc') 
        ->paginate(10);

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

    //Metodo para crear registro en Base de datos
    public function store(Request $request)
    {
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

        // 2. Mapeamos los datos para la sesion
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

        $uuid = Str::uuid()->toString();

        session()->put('wizard_equipo.uuid', $uuid);
        session()->put('wizard_equipo.equipo', $data);

        return redirect()->route('equipos.wizard-ubicacion', $uuid);
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
            'tipo_activo_id'    => 'required|exists:tipo_activos,id',
            'usuario_id'        => 'required|exists:users,id',
            'ubicacion_id'      => 'nullable|exists:ubicaciones,id',
            'serial'            => 'required|string|max:255',
            'fecha_adquisicion' => 'required|date',
            'valor_inicial'     => 'nullable|numeric',
            'sistema_operativo' => 'nullable|string',
        ]);

        $viejosDatos = $equipo->toArray();
        $motivos = $request->input('motivos', []);

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

        if ($request->filled('vida_util_estimada') && $request->filled('vida_util_unidad')) {
            $data['vida_util_estimada'] = $request->vida_util_estimada . ' ' . $request->vida_util_unidad;
        }

        $cambiosDetectados = [];
        foreach ($data as $campo => $nuevoValor) {
        $valorAnterior = $viejosDatos[$campo] ?? null;

        if ($nuevoValor != $valorAnterior) {
            $cambiosDetectados[$campo] = [
                'antes'   => $valorAnterior,
                'despues' => $nuevoValor,
                'motivo'  => $motivos[$campo] ?? 'Sin motivo especificado' 
            ];
        }
    }

    $equipo->update($data);

    // dd("El equipo ya se actualizó. Revisa la base de datos. ¿Ya existe un log gris?");

    // if (!empty($cambiosDetectados)) {
    //         Historial_log::create([
    //             'activo_id'     => $equipo->id,
    //             'usuario_accion_id' =>$equipo->usuario_id,
    //             'tipo_registro' => 'Actualización',
    //             'detalles_json' => ['cambios' => $cambiosDetectados],
    //             'user_id'       => auth()->id(),
    //             'created_at'    => now()
    //         ]);
    //     }

        $this->syncRelation($equipo->perifericos(),  $request->input('periferico', []));
        $this->syncRelation($equipo->rams(),         $request->input('ram', []));
        $this->syncRelation($equipo->procesadores(), $request->input('procesador', []));
        $this->syncRelation($equipo->monitores(),    $request->input('monitor', []));
        $this->syncRelation($equipo->discosDuros(),  $request->input('discoDuro', []));

        $perPage = 10;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()->route('equipos.index', ['page' => $page])
        ->with('warning', 'Equipo actualizado
        <a href="#" class="btn-historial-alert">
        <i class="fas fa-history mr-1"></i> Ver en el Historial
        </a>')
        ->with('actualizado_id', $equipo->id)   
        ;
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
