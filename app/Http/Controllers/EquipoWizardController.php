<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class EquipoWizardController extends Controller
{
    /**
     * PASO 1: Formulario Base e Inicializaci n.
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
     * PROCESO PASO 1: Validaci n y creaci n del UUID.
     * Aqu  nace la "Persistencia Temporal" en la sesi n.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marca_id' => 'required|integer|exists:marcas,id',
            'tipo_activo_id' => 'required|integer|exists:tipo_activos,id',
            'serial' => 'nullable|string|max:255',
            'sistema_operativo' => 'required|string|max:35', 
            'usuario_id' => 'required|integer|exists:users,id',
            'valor_inicial' => 'nullable|numeric|min:0|max:99999999.99',
            'fecha_adquisicion' => 'required|date',
            'vida_util_estimada' => 'required|string|max:255',
        ]);

        $data = $validated;

        // L gica de campos de respaldo (Valores por defecto)
        $data['serial'] = $data['serial'] ?? 'INT-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $data['valor_inicial'] = $data['valor_inicial'] ?? 0;

        // Generaci n del identificador  nico para el recorrido de la URL
        $uuid = Str::uuid()->toString();
        
        // Guardamos en sesi n el UUID y los datos base del equipo
        session()->put('wizard_equipo.uuid', $uuid);
        session()->put('wizard_equipo.equipo', $data);


        return redirect()->route('equipos.wizard-ubicacion', $uuid);
    }

    /**
     * PASO 2: Ubicaci n.
     * Valida que el UUID de la URL coincida con la sesi n activa.
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
        
        // Agregamos el array de ubicaci n a la sesi n vol til
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

        // Quitamos campos nulos para no ensuciar la sesi n
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
        ]);

        $datos = array_filter($request->only(['capacidad', 'tipo_hdd_ssd', 'interface']));

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
            'tipo_chz' => 'nullable|string'
        ]);

        $datos = array_filter($request->only(['capacidad_gb', 'clock_mhz', 'tipo_chz']));

        if (empty($datos)) {
            session()->forget('wizard_equipo.ram');
        } else {
            session()->put('wizard_equipo.ram', $datos);
        }

        return redirect()->route('equipos.wizard-periferico', $uuid);
    }

    /**
     * PASO 6: Perif ricos.
     */
    public function perifericoForm($uuid)
    {
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-periferico', compact('equipo', 'uuid'));
    }

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

        return redirect()->route('equipos.wizard-procesador', $uuid);
    }

    /**
     * PASO 7: Formulario de Procesador.
     *  ltima etapa de recolecci n de datos antes del guardado definitivo.
     */
    public function ProcesadorForm($uuid)
    {
        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');
        return view('equipos.wizard-procesador', compact('uuid', 'equipo'));
    }

    /**
     * PROCESO FINAL: Consolidaci n de datos en Base de Datos.
     * Este m todo vac a la "Memoria Temporal" y crea los registros reales.
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

        $wizard = session('wizard_equipo');
        if (!$wizard || $wizard['uuid'] !== $uuid) {
            abort(403, 'Sesi n inv lida al intentar finalizar el registro.');
        }

        // --- INICIO DE PERSISTENCIA EN BASE DE DATOS ---

        // A. Crear el Equipo Base
        // Usamos el operador spread (...) para traer los datos del equipo y sobreescribimos/a adimos la ubicacion
        // A. Crear el Equipo Base mapeando manualmente cada campo
        //Captura del Observer
        $equipo = Equipo::create([
            'serial'             => $wizard['equipo']['serial'],
            'usuario_id'         => $wizard['equipo']['usuario_id'],
            'valor_inicial'      => $wizard['equipo']['valor_inicial'],
            'fecha_adquisicion'  => $wizard['equipo']['fecha_adquisicion'],
            'vida_util_estimada' => $wizard['equipo']['vida_util_estimada'],
            'sistema_operativo'  => $wizard['equipo']['sistema_operativo'],
            'ubicacion_id'       => $wizard['ubicacion']['ubicacion_id'] ?? null,
            'marca_id'       => $wizard['equipo']['marca_id'], 
            'tipo_activo_id'        => $wizard['equipo']['tipo_activo_id'],
        ]);

        // B. Crear relaciones mediante el m todo create() de Eloquent
        // Solo se ejecutan si el usuario llen  los campos (evitamos registros vac os)

        if (!empty($wizard['monitor'])) {
            $equipo->monitores()->create($wizard['monitor']);
        }

        if (!empty($wizard['disco_duro'])) {
            $equipo->discosDuros()->create($wizard['disco_duro']);
        }
    
        if (!empty($wizard['ram'])) {
            $equipo->rams()->create($wizard['ram']);
        }

        if (!empty($wizard['periferico'])) {
            $equipo->perifericos()->create($wizard['periferico']);
        }

        if (!empty($wizard['procesador'])) {
            $equipo->procesadores()->create($wizard['procesador']);
        }

        // --- CIERRE DE PROCESO ---

        // 4. Limpiar la mochila de la sesi n
        session()->forget('wizard_equipo');

        // 5. C lculo inteligente de paginaci n para redirigir al usuario al nuevo registro
        $perPage = 11;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()->route('equipos.index', ['page' => $page])
            ->with('success', ' Equipo y todos sus componentes registrados con exito!')
            ->with('new_id', $equipo->id);
    }
   
    

}


