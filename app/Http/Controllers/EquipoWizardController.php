<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Historial_log;   

/*
|--------------------------------------------------------------------------
| Controlador principal para Formulario de Alta de un equipo
|--------------------------------------------------------------------------
*/

class EquipoWizardController extends Controller
{
    /**
     * Metodo para cargar vista de creacion de equipo Base
     * PASO 1: Formulario Base e Inicializacion.
     * Carga los usuarios y prepara la sesi n para el flujo.
     */
    public function create()
    {
        $wizard = session('wizard_equipo');
        $usuarios = User::select('id', 'name')->get();
        $equipo = data_get($wizard, 'equipo', []);
        return view('equipos.create', compact('equipo', 'usuarios'));
    }

    /**
     * Metodo para cargar en la sesion los datos dados por el usuario
     * PROCESO PASO 1: Validaci n y creaci n del UUID.
     * Aqu  nace la "Persistencia Temporal" en la sesi n.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marca_id' => 'required|integer|exists:marcas,id',
            'modelo'             => 'required|string|max:100',
            'tipo_activo_id' => 'required|integer|exists:tipo_activos,id',
            'serial' => 'nullable|string|max:255',
            'sistema_operativo' => 'required|string|max:35', 
            'usuario_id' => 'required|integer|exists:users,id',
            'valor_inicial' => 'nullable|numeric|min:0|max:99999999.99',
            'fecha_adquisicion' => 'required|date',
            'vida_util_estimada' => 'required|string|max:255',
        ]);

        $data = $validated;

        // Logica de campos de respaldo (Valores por defecto, en caso de que el usuario no los ingrese)
        $data['serial'] = $data['serial'] ?? 'INT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $data['valor_inicial'] = $data['valor_inicial'] ?? 0;

        // Generacion del identificador unico para el recorrido de la URL
        $uuid = Str::uuid()->toString();
        
        // Guardamos en sesion el UUID y los datos base del equipo
        session()->put('wizard_equipo.uuid', $uuid);
        session()->put('wizard_equipo.equipo', $data);
        return redirect()->route('equipos.wizard-ubicacion', $uuid);
    }

    /**
     * Metodo para cargar en la sesion los datos dados por el usuario
     * PASO 2: Ubicacion.
     * Valida que el UUID de la URL coincida con la sesion activa.
     */
    public function ubicacionForm($uuid)
    {
        $wizard = session('wizard_equipo');

        if (!$wizard || $wizard['uuid'] !== $uuid) {
            abort(403, 'Sesi n de wizard inv lida o expirada.');
        }

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-ubicacion', compact('equipo', 'uuid'));
    }

    /**
     * PROCESO PASO 2: Guardar Ubicaci n.
     * Almacena la relaci n de ubicaci n en un apartado separado de la sesi n.
     */
    public function saveUbicacion(Request $request)
    {
        $request->validate([
            'ubicacion_id' => 'required|exists:ubicaciones,id',
        ]);

        $wizard = session('wizard_equipo');
        
        // Agregamos el array de ubicacion a la sesion
        session()->put('wizard_equipo.ubicacion', [
            'ubicacion_id' => $request->ubicacion_id,
        ]);

        $uuid = $wizard['uuid'];
        return redirect()->route('equipos.wizard-monitores', $uuid);
    }

    /**
     * PASO 3: Monitores.
     */
    public function monitoresForm($uuid)
    {
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403, 'Acceso no autorizado.');

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-monitores', compact('equipo', 'uuid'));
    }

    public function saveMonitor(Request $request, $uuid)
    {
        $request->validate([
            'marca' => 'nullable|string',
            'serial' => 'nullable|string',
            'escala_pulgadas' => 'nullable|string',
            'interface' => 'nullable|string'
        ]);

        // Quitamos campos nulos para no ensuciar la sesion
        $datos = array_filter($request->only(['marca', 'serial', 'escala_pulgadas', 'interface']));

        if (empty($datos)) {
            session()->forget('wizard_equipo.monitor');
        } else {
            session()->put('wizard_equipo.monitor', $datos);
        }

        return redirect()->route('equipos.wizard-discos_duros', $uuid);
    }

    /**
     * PASO 4: Discos Duros.
     */
    public function discoduroForm($uuid)
    {
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-discos_duros', compact('equipo', 'uuid'));
    }

    public function saveDiscoduro(Request $request, $uuid)
    {
        $request->validate([
            'capacidad' => 'nullable|string',
            'tipo_hdd_ssd' => 'nullable|string',
            'interface' => 'nullable|string',
            'serial' => 'nullable|string'
        ]);

        $datos = array_filter($request->only(['capacidad', 'tipo_hdd_ssd', 'interface','serial']));

        if (empty($datos)) {
            session()->forget('wizard_equipo.disco_duro');
        } else {
            session()->put('wizard_equipo.disco_duro', $datos);
        }

        return redirect()->route('equipos.wizard-ram', $uuid);
    }

    /**
     * PASO 5: Memoria RAM.
     */
    public function ramForm($uuid)
    {
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-rams', compact('equipo', 'uuid'));
    }

    public function saveRam(Request $request, $uuid)
    {
        $request->validate([
            'capacidad_gb' => 'nullable|string',
            'clock_mhz' => 'nullable|string',
            'tipo_chz' => 'nullable|string',
            'serial' => 'nullable|string',
        ]);

        $datos = array_filter($request->only(['capacidad_gb', 'clock_mhz', 'tipo_chz', 'serial']));

        if (empty($datos)) {
            session()->forget('wizard_equipo.ram');
        } else {
            session()->put('wizard_equipo.ram', $datos);
        }

        return redirect()->route('equipos.wizard-procesador', $uuid);
    }

    /**
     * PASO 6: Perifiricos.
     */
 /**
     * PASO: Formulario de Procesador (Ahora es el penúltimo)
     */
    public function procesadorForm($uuid)
    {
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-procesador', compact('uuid', 'equipo'));
    }

    /**
     * Guarda Procesador y salta a Periférico
     */
    public function saveProcesador(Request $request, $uuid)
    {
        $request->validate([
            'marca' => 'nullable|string|max:255',
            'descripcion_tipo' => 'nullable|string|max:255',
            'frecuenciaMicro' => 'nullable|string|max:100',
        ]);

        $datos = array_filter($request->only(['marca', 'descripcion_tipo', 'frecuenciaMicro']));

        if (empty($datos)) {
            session()->forget('wizard_equipo.procesador');
        } else {
            session()->put('wizard_equipo.procesador', $datos);
        }

        // REDIRIGE AL ÚLTIMO PASO: Periférico
        return redirect()->route('equipos.wizard-periferico', $uuid);
    }

    /**
     * PASO FINAL: Formulario de Periférico
     */
    public function perifericoForm($uuid)
    {
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-periferico', compact('equipo', 'uuid'));
    }

    /**
     * PROCESO FINAL: Guarda Periférico y consolida todo en la Base de Datos
     */
    public function savePeriferico(Request $request, $uuid)
    {
        $request->validate([
            'tipo' => 'nullable|string',
            'marca' => 'nullable|string',
            'serial' => 'nullable|string',
            'interface' => 'nullable|string',
        ]);

        $datos = array_filter($request->only(['tipo', 'marca', 'serial', 'interface']));

        if (empty($datos)) {
            session()->forget('wizard_equipo.periferico');
        } else {
            session()->put('wizard_equipo.periferico', $datos);
        }

        // Recuperar toda la sesión acumulada
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) {
            abort(403, 'Sesión inválida al intentar finalizar el registro.');
        }

        // 1. Crear el registro principal del Equipo
        $equipo = Equipo::create([
            'serial'             => $wizard['equipo']['serial'],
            'usuario_id'         => $wizard['equipo']['usuario_id'],
            'valor_inicial'      => $wizard['equipo']['valor_inicial'],
            'fecha_adquisicion'  => $wizard['equipo']['fecha_adquisicion'],
            'vida_util_estimada' => $wizard['equipo']['vida_util_estimada'],
            'sistema_operativo'  => $wizard['equipo']['sistema_operativo'],
            'modelo'             => $wizard['equipo']['modelo'],
            'ubicacion_id'       => $wizard['ubicacion']['ubicacion_id'] ?? null,
            'marca_id'           => $wizard['equipo']['marca_id'], 
            'tipo_activo_id'     => $wizard['equipo']['tipo_activo_id'],
        ]);

        // 2. Crear relaciones (Monitor, RAM, Disco, etc.) de manera silenciosa
        Equipo::withoutEvents(function () use ($equipo, $wizard) {
            if (!empty($wizard['monitor'])) $equipo->monitores()->create($wizard['monitor']);
            if (!empty($wizard['disco_duro'])) $equipo->discosDuros()->create($wizard['disco_duro']);
            if (!empty($wizard['ram'])) $equipo->rams()->create($wizard['ram']);
            if (!empty($wizard['periferico'])) $equipo->perifericos()->create($wizard['periferico']);
            if (!empty($wizard['procesador'])) $equipo->procesadores()->create($wizard['procesador']);
        });

        // 3. Generar el Resumen de Hardware para el LOG
        $equipo->load(['procesadores', 'rams', 'discosDuros', 'monitores', 'perifericos', 'marca', 'tipoActivo', 'usuario']);
        
        $resumenHardware = [
            'Procesador' => $equipo->procesadores->first() 
                ? collect([$equipo->procesadores->first()->marca, $equipo->procesadores->first()->descripcion_tipo ? '(' . $equipo->procesadores->first()->descripcion_tipo . ')' : null])->filter()->implode(' ') 
                : 'No asignado',
            'RAM' => $equipo->rams->first() 
                ? collect([$equipo->rams->first()->capacidad_gb . 'GB', '[' . $equipo->rams->first()->tipo_ram . ']', $equipo->rams->first()->clock_mhz . 'MHz'])->filter()->implode(' • ')
                : 'No detectada',
            'Disco Duro' => $equipo->discosDuros->first() 
                ? collect([$equipo->discosDuros->first()->capacidad, $equipo->discosDuros->first()->tipo_hdd_ssd, '[' . $equipo->discosDuros->first()->interface . ']'])->filter()->implode(' • ') 
                : 'No detectado',
            'Monitor' => $equipo->monitores->first() 
                ? collect([$equipo->monitores->first()->marca, $equipo->monitores->first()->escala_pulgadas . '"', '[' . $equipo->monitores->first()->interface . ']'])->filter()->implode(' • ') 
                : 'No detectado',
            'Periferico' => $equipo->perifericos->first() 
                ? collect([$equipo->perifericos->first()->tipo, $equipo->perifericos->first()->marca ? '• ' . $equipo->perifericos->first()->marca : null, '[' . $equipo->perifericos->first()->interface . ']'])->filter()->implode(' ') 
                : 'No detectado',
        ];

        $hardwareString = collect($resumenHardware)->map(fn($v, $k) => "$k: $v")->implode(' | ');

        // 4. Crear el Historial Log
        Historial_log::create([
            'activo_id'         => $equipo->id,
            'usuario_accion_id' => auth()->id() ?? 1,
            'tipo_registro'     => 'Creacion',
            'detalles_json'     => [
                'mensaje' => 'Registro integral de nuevo activo y componentes.',
                'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                'cambios' => [
                    'Usuario Asignado'     => ['antes' => 'N/A', 'despues' => $equipo->usuario->name ?? 'No encontrado'],
                    'Marca del Equipo'     => ['antes' => 'N/A', 'despues' => $equipo->marca->nombre ?? 'No generado'],
                    'Tipo de Activo'       => ['antes' => 'N/A', 'despues' => $equipo->tipoActivo?->nombre ?? 'No disponible'],
                    'Serial'               => ['antes' => 'N/A', 'despues' => $equipo->serial ?? 'No registrado'],
                    'Hardware Inicial'     => ['antes' => 'N/A', 'despues' => $hardwareString],
                    'Sistema Operativo'    => ['antes' => 'N/A', 'despues' => str_replace('|', ' ', $equipo->sistema_operativo)],
                    'Valor Inicial'        => ['antes' => 'N/A', 'despues' => '$' . number_format($equipo->valor_inicial, 2)],
                    'Fecha de Adquisicion' => ['antes' => 'N/A', 'despues' => $equipo->fecha_adquisicion ?? 'No disponible'],
                    'Vida Util estimada'   => ['antes' => 'N/A', 'despues' => ($equipo->vida_util_estimada ?? 0) . ' años'],
                ]
            ]
        ]);

        // Limpiar sesión
        session()->forget('wizard_equipo');

        // Calcular paginación para el redirect
        $perPage = 10;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()->route('equipos.index', ['page' => $page])
            ->with('success', 'Equipo Creado Correctamente')
            ->with('new_id', $equipo->id);
    }   
    

}


