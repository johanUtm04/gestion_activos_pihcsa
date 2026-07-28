<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Http\Requests\StoreEquipoStep1;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Historial_log;
use Psy\Readline\Hoa\Console;

/*
|--------------------------------------------------------------------------
| Controlador principal para el flujo Wizard de alta de equipos
|--------------------------------------------------------------------------
| Gestiona un proceso multi-paso utilizando sesión como persistencia
| temporal hasta consolidar el registro final en base de datos.
|--------------------------------------------------------------------------
*/

class EquipoWizardController extends Controller
{
    /**
     * PASO 1:
     * Muestra el formulario base del equipo.
     * Recupera datos previos desde sesión si existen.
     */
    public function create()
    {
        // Obtiene datos temporales del equipo almacenados en sesión
        $equipo = session('wizard_equipo.equipo', []);

        // Obtiene usuarios ordenados alfabéticamente para selección
        $usuarios = User::select('id', 'name')->orderBy('name')->get();

        return view('equipos.wizard.create', compact('equipo', 'usuarios'));
    }

    /**
     * PASO 1 - Guardado inicial.
     * Valida datos base y genera UUID para el flujo wizard.
     */
    public function store(StoreEquipoStep1 $request)
    {
        // Datos validados mediante FormRequest
        $data = $request->validated();

        // Genera serial automático si no fue proporcionado
        if (empty($data['serial'])) {
            $data['serial'] = $this->generarSerialTemporal();
        }

        // Asegura valor inicial por defecto
        $data['valor_inicial'] ??= 0;

        // Genera identificador único para proteger el flujo
        $uuid = Str::uuid()->toString();

        // Guarda datos base y UUID en sesión
        session()->put('wizard_equipo.uuid', $uuid);
        session()->put('wizard_equipo.equipo', $data);

        return redirect()->route('equipos.wizard.ubicacion', $uuid);
    }

    /**
     * PASO 2:
     * Muestra formulario de ubicación validando integridad del flujo.
     */
    public function ubicacionForm($uuid)
    {
        $wizard = session('wizard_equipo');
        
        // Verifica que el flujo no haya sido alterado
        if (!$wizard || ($wizard['uuid'] ?? null) !== $uuid) {
            abort(403, 'Sesion de wizard invalida o expirada.');
        }

        $equipo = $wizard['equipo'] ?? [];

        return view('equipos.wizard.ubicacion', compact('equipo', 'uuid'));
    }

    /**
     * PASO 2 - Guardado de ubicación.
     * Almacena relación ubicación/departamento en sesión.
     */
    public function saveUbicacion(Request $request)
    {
        $validated = $request->validate([
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'departamento_perteneciente' => 'nullable'
        ]);

        // Guarda ubicación dentro del wizard
        session()->put('wizard_equipo.ubicacion', $validated);

        $uuid = session('wizard_equipo.uuid');

        return redirect()->route('equipos.wizard.monitor', $uuid);
    }

    /**
     * PASO 3:
     * Formulario de monitor.
     */
    public function monitoresForm($uuid)
    {
        $wizard = session('wizard_equipo');

        if (!$wizard || $wizard['uuid'] !== $uuid) {
            abort(403);
        }

        $equipo = $wizard['equipo'] ?? [];

        return view('equipos.wizard.monitor', compact('equipo', 'uuid'));
    }

    /**
     * PASO 3 - Guardado de monitor.
     * Persiste datos opcionales en sesión.
     */
    public function saveMonitor(Request $request, $uuid)
    {
        $request->validate([
            'marca' => 'nullable|string',
            'serial' => 'nullable|string',
            'escala_pulgadas' => 'nullable|string',
            'interface' => 'nullable|string'
        ]);

        $datos = array_filter($request->only([
            'marca', 'serial', 'escala_pulgadas', 'interface'
        ]));

        empty($datos)
            ? session()->forget('wizard_equipo.monitor')
            : session()->put('wizard_equipo.monitor', $datos);

        return redirect()->route('equipos.wizard.discoDuro', $uuid);
    }

    /**
     * PASO 4:
     * Formulario de disco duro.
     */
    public function discoduroForm($uuid)
    {
        $wizard = session('wizard_equipo');

        if (!$wizard || $wizard['uuid'] !== $uuid) {
            abort(403);
        }

        $equipo = data_get($wizard, 'equipo');

        return view('equipos.wizard.discoDuro', compact('equipo', 'uuid'));
    }

    /**
     * PASO 4 - Guardado de disco duro.
     */
    public function savediscoduro(Request $request, $uuid)
    {
        $request->validate([
            'capacidad' => 'nullable|string',
            'tipo_hdd_ssd' => 'nullable|string',
            'interface' => 'nullable|string',
            'serial' => 'nullable|string'
        ]);

        $datos = array_filter($request->only([
            'capacidad', 'tipo_hdd_ssd', 'interface', 'serial'
        ]));

        empty($datos)
            ? session()->forget('wizard_equipo.disco_duro')
            : session()->put('wizard_equipo.disco_duro', $datos);

        return redirect()->route('equipos.wizard.ram', $uuid);
    }

    /**
     * PASO 5:
     * Formulario RAM.
     */
    public function ramForm($uuid)
    {
        $wizard = session('wizard_equipo');

        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');

        return view('equipos.wizard.ram', compact('equipo', 'uuid'));
    }

    /**
     * PASO 5 - Guardado RAM.
     */
    public function saveRam(Request $request, $uuid)
    {
        $request->validate([
            'capacidad_gb' => 'nullable|string',
            'clock_mhz' => 'nullable|string',
            'tipo_chz' => 'nullable|string',
            'serial' => 'nullable|string',
        ]);

        $datos = array_filter($request->only([
            'capacidad_gb', 'clock_mhz', 'tipo_chz', 'serial'
        ]));

        empty($datos)
            ? session()->forget('wizard_equipo.ram')
            : session()->put('wizard_equipo.ram', $datos);

        return redirect()->route('equipos.wizard.procesador', $uuid);
    }

    /**
     * PASO 6:
     * Formulario procesador.
     */
    public function procesadorForm($uuid)
    {
        $wizard = session('wizard_equipo');

        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');


        return view('equipos.wizard.procesador', compact('uuid', 'equipo'));
    }

    /**
     * PASO 6 - Guardado procesador.
     */
    public function saveProcesador(Request $request, $uuid)
    {
        $request->validate([
            'marca' => 'nullable|string|max:255',
            'descripcion_tipo' => 'nullable|string|max:255',
            'clock_ghz' => 'nullable|string|max:100',
        ]);

        $datos = array_filter($request->only([
            'marca', 'descripcion_tipo', 'clock_ghz'
        ]));

        empty($datos)
            ? session()->forget('wizard_equipo.procesador')
            : session()->put('wizard_equipo.procesador', $datos);

        return redirect()->route('equipos.wizard.periferico', $uuid);
    }

    /**
     * PASO FINAL: Formulario de Periférico
     */
    public function perifericoForm($uuid)
    {
        $wizard = session('wizard_equipo');

        if (!$wizard || $wizard['uuid'] !== $uuid) abort(403);

        $equipo = data_get($wizard, 'equipo');
        
        return view('equipos.wizard.periferico', compact('equipo', 'uuid'));
    }

    /**
     * PASO FINAL:
     * Consolida todos los datos y guarda en base de datos.
     */
    public function savePeriferico(Request $request, $uuid)
    {
        //SE DEJA
        //1.-Ocurre la validacion
        $request->validate([
            'tipo' => 'nullable|string',
            'marca' => 'nullable|string',
            'serial' => 'nullable|string',
            'interface' => 'nullable|string',
        ]);

        //SE DEJA
        //2.-Guardamos el último paso en la sesión
        $datos = array_filter($request->only(['tipo', 'marca', 'serial', 'interface']));
        if (empty($datos)) {
            session()->forget('wizard_equipo.periferico');
        } else {
            session()->put('wizard_equipo.periferico', $datos);
        }

        //3.- Tomamos la sesion en esa variable
        // ESTO SE DEJA
        $wizard = session('wizard_equipo');

        //SE DEJA
        if (!$wizard || $wizard['uuid'] !== $uuid) {
            abort(403, 'Sesión expirada.');
        }

        // 4. Instanciar el Equipo (Sin guardarlo aún)
        $equipo = $this->instanciarEquipoDesdeWizard($wizard);

        $equipo->save(); //Se dispara el ID y el observer //Esto no quedaria pq ya estaria en el obsever
        
        //Recorre los componentes del wizard y crea uno o varios registros para el equipo, sin activar eventos de Laravel.
        $this->vincularComponentesWizard($equipo, $wizard);

            //SE QUEDA O BUENO SI SE PUEDE AL OBSERVERV
            // 3. Ahora sí podemos armar el resumen porque ya hay datos en la DB -Ve a la bodega y traeme estas cajas (5)-
            $equipo->load(['procesadores', 'rams', 'discosDuros', 'monitores', 'perifericos']);

            //Pasar al observer el resumen del hardware
            //OBSERVER
            $hardwareString = $this->armarResumenHardware($equipo);
            $equipo->resumen_temporal = $hardwareString;
            
            //OBSERVER
            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => auth()->id() ?? 1,
                'tipo_registro'     => 'Creacion',
                'detalles_json'     => [
                    'mensaje' => 'Registro integral de nuevo activo y componentes',
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'cambios' => [
                        // --- DATOS BASE ---
                        'Tipo de Equipo'    => ['antes' => 'N/A', 'despues' => $equipo->tipoActivo->nombre ?? 'SIN-TIPO'],
                        'Usuario Asignado'  => ['antes' => 'N/A', 'despues' => $equipo->usuario->name ?? 'Sin Nombre'],
                        'Serial Equipo'     => ['antes' => 'N/A', 'despues' => $equipo->serial],
                        'Modelo'            => ['antes' => 'N/A', 'despues' => $equipo->modelo ?? 'N/A'],
                        'Marca'             => ['antes' => 'N/A', 'despues' => $equipo->marca->nombre ?? 'N/A'],
                        'Ubicación'         => ['antes' => 'N/A', 'despues' => $equipo->ubicacion->nombre ?? 'N/A'], 
                        'Departamente'      => ['antes' => 'N/A', 'despues' => $equipo->departamento_perteneciente ?? 'N/A'], 
                        'Sistema Operativo' => ['antes' => 'N/A', 'despues' => str_replace('|', ', ', $equipo->sistema_operativo)],
                        'Valor Inicial'     => ['antes' => 'N/A', 'despues' => '$' . number_format((float)$equipo->valor_inicial, 2)],
                        'Fecha Adquisición' => ['antes' => 'N/A', 'despues' => $equipo->fecha_adquisicion ?? 'N/A'],
                        'Vida Útil Estimada' => ['antes' => 'N/A', 'despues' => $equipo->vida_util_estimada . ' años'],
                        // --- HARDWARE ADICIONAL ---
                        'Hardware Inicial'  => ['antes' => 'N/A', 'despues' => $equipo->resumen_temporal ?? 'N/A'],
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

    private function crearComponente($equipo, $tipo, $data) {
        $rel = ['monitor'=>'monitores','disco_duro'=>'discosDuros','ram'=>'rams','periferico'=>'perifericos','procesador'=>'procesadores'];
        $equipo->{$rel[$tipo]}()->create($data);
    }

    private function instanciarEquipoDesdeWizard(array $wizard)
    {
        return new \App\Models\Equipo([
            'serial'                     => $wizard['equipo']['serial'],
            'usuario_id'                 => $wizard['equipo']['usuario_id'],
            'valor_inicial'              => $wizard['equipo']['valor_inicial'],
            'fecha_adquisicion'          => $wizard['equipo']['fecha_adquisicion'],
            'vida_util_estimada'         => $wizard['equipo']['vida_util_estimada'],
            'sistema_operativo'          => $wizard['equipo']['sistema_operativo'],
            'modelo'                     => $wizard['equipo']['modelo'],
            'marca_id'                   => $wizard['equipo']['marca_id'], 
            'tipo_activo_id'             => $wizard['equipo']['tipo_activo_id'],
            'pedimento'                  => $wizard['equipo']['pedimento'] ?? null, 
            'ubicacion_id'               => $wizard['ubicacion']['ubicacion_id'] ?? null,
            'departamento_perteneciente' => $wizard['ubicacion']['departamento_perteneciente'] ?? null,
        ]);
    }

    private function vincularComponentesWizard($equipo, array $wizard)
    {
    Equipo::withoutEvents(function () use ($equipo, $wizard) {
        $tiposComponentes = ['monitor', 'disco_duro', 'ram', 'periferico', 'procesador'];

        foreach ($tiposComponentes as $key) {
            if (!empty($wizard[$key])) {
                // Si el componente viene como una lista (ej. varios procesadores o RAMs)
                if (isset($wizard[$key][0]) && is_array($wizard[$key][0])) {
                    foreach ($wizard[$key] as $item) {
                        $this->crearComponente($equipo, $key, $item);
                    }
                } else {
                    // Si es un componente único
                    $this->crearComponente($equipo, $key, $wizard[$key]);
                }
            }
        }
    });
    }

    private function armarResumenHardware(Equipo $equipo): string
    {
        $resumen = [
            'Procesador' => $equipo->procesadores->first() 
                ? collect([
                    $equipo->procesadores->first()->marca,
                    $equipo->procesadores->first()->clock_ghz,
                    $equipo->procesadores->first()->descripcion_tipo ? "({$equipo->procesadores->first()->descripcion_tipo})" : null
                ])->filter()->implode(' ') 
                : 'N/A',

            'RAM' => $equipo->rams->first() 
                ? collect([
                    $equipo->rams->first()->capacidad_gb ? "{$equipo->rams->first()->capacidad_gb}GB" : null,
                    $equipo->rams->first()->tipo_chz,
                    $equipo->rams->first()->clock_mhz ? "{$equipo->rams->first()->clock_mhz}MHz" : null,
                ])->filter()->implode(' • ') 
                : 'N/A',

            'Disco' => $equipo->discosDuros->first() 
                ? collect([
                    $equipo->discosDuros->first()->capacidad,
                    $equipo->discosDuros->first()->tipo_hdd_ssd,
                    $equipo->discosDuros->first()->interface,
                ])->filter()->implode(' • ') 
                : 'N/A',

            'Monitor' => $equipo->monitores->first() 
                ? collect([
                    $equipo->monitores->first()->marca,
                    $equipo->monitores->first()->escala_pulgadas ? "{$equipo->monitores->first()->escala_pulgadas}\"" : null,
                    $equipo->monitores->first()->interface,
                ])->filter()->implode(' • ') 
                : 'N/A',

            'Periférico' => $equipo->perifericos->first() 
                ? collect([
                    $equipo->perifericos->first()->tipo,
                    $equipo->perifericos->first()->marca ? "({$equipo->perifericos->first()->marca})" : null,
                    $equipo->perifericos->first()->interface,
                ])->filter()->implode(' • ') 
                : 'N/A',
        ];

        return collect($resumen)->map(fn($v, $k) => "**$k**: $v")->implode(' | ');
    }

    /**
     * Genera un serial por defecto siguiendo el patrón INT-AÑO-CORRELATIVO
     * Si lees esto suerte mi may xd, Johan Estuvo aqui 3 de marzo de 2026
     */
    private function generarSerialTemporal(): string
    {
        $anio = date('Y');
        $random = mt_rand(1, 999);
        
        return sprintf('INT-%s-%03d', $anio, $random);
    }

    public function validarSerial(Request $request)
    {
        $serial = strtoupper(trim($request->serial));
        
        if (empty($serial)) {
            return response()->json(['disponible' => true]);
        }

        $existe = \App\Models\Equipo::where('serial', $serial)
                    ->where('id', '!=', $request->equipo_id) 
                    ->exists();

        return response()->json([
            'disponible' => !$existe,
            'mensaje' => $existe ? 'Este serial ya está registrado en PIHCSA' : 'Serial disponible'
        ]);
    }

}


