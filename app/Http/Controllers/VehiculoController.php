<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\CatTipoVehiculo;
use App\Models\Marca;
use App\Models\Ubicacion;
use App\Models\User;
use App\Models\MantenimientoVehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class VehiculoController extends Controller
{
    /**
     * Muestra el inventario principal.
     */
public function index(Request $request)
{
    // Construimos la consulta base con sus relaciones
    $query = Vehiculo::with([
        'tipoVehiculo',
        'marca',
        'ubicacion',
        'usuario',
        'documentacion',
        'empresa',
    ]);

    // Filtro por Tipo de Vehículo
    if ($request->filled('tipo_vehiculo_id')) {
        $query->where('tipo_vehiculo_id', $request->tipo_vehiculo_id);
    }

    // Filtro por Marca
    if ($request->filled('marca_id')) {
        $query->where('marca_id', $request->marca_id);
    }

    // Filtro por Ubicación
    if ($request->filled('ubicacion_id')) {
        $query->where('ubicacion_id', $request->ubicacion_id);
    }

    // Filtro por Estatus (Activos / Inactivos)
    if ($request->filled('estatus')) {
        $query->where('is_active', $request->estatus);
    }

    // Búsqueda libre (Placas, Modelo o Número de Serie)
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;
        $query->where(function($q) use ($buscar) {
            $q->where('placas', 'LIKE', "%{$buscar}%")
              ->orWhere('modelo', 'LIKE', "%{$buscar}%")
              ->orWhere('no_serie', 'LIKE', "%{$buscar}%");
        });
    }

    // Ejecutamos la consulta filtrada
    $vehiculos = $query->get();

    // Catálogos para los selects del buscador y modales
    $tiposVehiculo = CatTipoVehiculo::select('id', 'nombre')->get();
    $marcas = Marca::where('tipo', 'AUTO')
    ->orderBy('nombre')
    ->get();
    $usuarios      = User::select('id', 'name')->get();
    $ubicaciones   = Ubicacion::select('id', 'nombre')->get();

    return view('vehiculos.index', compact('vehiculos', 'tiposVehiculo', 'marcas', 'usuarios', 'ubicaciones'));
}

    /**
     * Endpoint que retorna los catálogos para rellenar los selects vía Fetch API.
     */
    public function filtros()
    {
        return response()->json([
            'tipos'       => CatTipoVehiculo::select('id', 'nombre')->get(),
            'marcas' => Marca::where('tipo', 'AUTO')
            ->select('id', 'nombre')
            ->orderBy('nombre')
            ->get(),
            'ubicaciones' => Ubicacion::select('id', 'nombre')->get(),
            'usuarios'    => User::select('id', 'name')->get(),
        ]);
    }

    /**
     * Almacena un vehículo nuevo.
     */
public function store(Request $request)
{

    $validated = $request->validate([
        'tipo_vehiculo_id' => 'required|exists:cat_tipo_vehiculos,id',
        'marca_id' => [
            'required',
            Rule::exists('marcas', 'id')->where('tipo', 'AUTO'),
        ],
        'usuario_id'       => 'required|exists:users,id',
        'ubicacion_id'     => 'required|exists:ubicaciones,id',
        'modelo'           => 'required|string|max:255',
        'anio'             => 'required|integer|min:1900|max:'.(date('Y') + 1),
        
        // Opcionales que ya tenías
        'placas'           => 'nullable|string|max:20',
        'no_serie'         => 'nullable|string|max:50',
        'no_motor'         => 'nullable|string|max:50',
        'cilindros'        => 'nullable|integer|min:1',
        'cuenta_contable'        => ['nullable', 'string', 'max:100'],
        'pedimento'        => ['nullable', 'string', 'max:100'],
        'tipo_combustible' => 'nullable|string',
        'fecha_ultimo_mantenimiento' => 'nullable|date',
        
        // NUEVOS: Agrega los campos financieros que te faltaban aquí
        'valor_inicial'      => 'nullable|numeric|min:0',
        'fecha_adquisicion'  => 'nullable|date',
        'vida_util_estimada' => 'nullable|integer|min:0',
        'fecha_inicio_uso'   => 'nullable|date', // Verifica si en tu BD se llama así
        
        'empresa_id'       => 'required|exists:empresas,id', 
    ]);

    $validated['is_active'] = true;

    if (!isset($validated['empresa_id'])) {
        $validated['empresa_id'] = session('empresa_id');
    }

    Vehiculo::create($validated);

    return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado exitosamente.');
}
    /**
     * Doble propósito: Si es petición AJAX, retorna JSON (para editar).
     * Si es una navegación común, retorna la vista de detalles.
     */
    public function show(Request $request, $id)
    {
        $vehiculo = Vehiculo::with(['tipoVehiculo', 'marca', 'ubicacion', 'usuario', 'documentacion'])->findOrFail($id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($vehiculo);
        }

        // Historial de mantenimientos para mostrarlo en la ficha del vehículo,
        // con los archivos adjuntos (orden de servicio / factura) si existen.
        $mantenimientos = MantenimientoVehiculo::where('vehiculo_id', $vehiculo->id)
            ->with('usuario')
            ->orderByDesc('fecha_evento')
            ->get();

        return view('vehiculos.show', compact('vehiculo', 'mantenimientos'));
    }

    /**
     * Actualiza los datos del vehículo y maneja la inactivación operativa.
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'tipo_vehiculo_id' => 'required|exists:cat_tipo_vehiculos,id',
            'marca_id' => [
                'required',
                Rule::exists('marcas', 'id')->where('tipo', 'AUTO'),
            ],
            'usuario_id'       => 'required|exists:users,id',
            'ubicacion_id'     => 'required|exists:ubicaciones,id',

            'modelo'           => 'required|string|max:255',
            'anio'             => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'placas'           => 'nullable|string|max:20',
            'no_serie'         => 'nullable|string|max:50',
            'no_motor'         => 'nullable|string|max:50',
            'cilindros'        => 'nullable|integer|min:1',

            'cuenta_contable'        => 'nullable|string|max:100',
            'pedimento'        => 'nullable|string|max:100',

            'tipo_combustible' => 'nullable|string|max:50',

            'valor_inicial'    => 'nullable|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
            'vida_util_estimada' => 'nullable|integer|min:0',
            'fecha_ultimo_mantenimiento' => 'nullable|date',

            'is_active'        => 'required|boolean',
            'motivo_inactivacion' => 'required_if:is_active,0|nullable|string|max:255',
        ]);

        // Si se vuelve a activar, limpiamos el motivo anterior de forma automática
        if ((int) $validated['is_active'] === 1) {
            $validated['motivo_inactivacion'] = null;
        }

        $vehiculo->update($validated);

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado correctamente.');
    }

    /**
     * Elimina físicamente o lógicamente el registro.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->update([
            'is_active' => false,
            'motivo_inactivacion' => 'Baja operativa desde inventario de vehículos.',
        ]);

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Vehículo enviado a inactivos correctamente.');
    }

    public function edit(Vehiculo $vehiculo)
    {
        $vehiculo->load([
            'empresa',
            'tipoVehiculo',
            'marca',
            'usuario',
            'ubicacion',
            'documentacion',
        ]);

        $tiposVehiculo = CatTipoVehiculo::select('id', 'nombre', 'frecuencia_meses')->get();
        $marcas = Marca::where('tipo', 'AUTO')
        ->select('id', 'nombre')
        ->orderBy('nombre')
        ->get();
        $usuarios      = User::select('id', 'name')->get();
        $ubicaciones   = Ubicacion::select('id', 'nombre')->get();

        return view('vehiculos.edit', compact(
            'vehiculo',
            'tiposVehiculo',
            'marcas',
            'usuarios',
            'ubicaciones'
        ));
    }


    public function getIndicadoresOperativosAttribute()
{
    return [
        $this->indicadorMantenimiento(),
        $this->indicadorSeguro(),
        $this->indicadorVidaUtil(),
    ];
}

private function indicadorMantenimiento(): array
{
    if (
        !$this->fecha_ultimo_mantenimiento ||
        !$this->tipoVehiculo ||
        !$this->tipoVehiculo->frecuencia_meses ||
        $this->tipoVehiculo->frecuencia_meses <= 0
    ) {
        return [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-tools',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay frecuencia o último mantenimiento registrado',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    }

    $ultimo = Carbon::parse($this->fecha_ultimo_mantenimiento);
    $proximo = $ultimo->copy()->addMonths($this->tipoVehiculo->frecuencia_meses);
    $hoy = Carbon::now();

    $diasTotales = max($ultimo->diffInDays($proximo), 1);
    $diasRestantes = $hoy->diffInDays($proximo, false);

    $score = max(0, min(100, round(($diasRestantes / $diasTotales) * 100)));

    if ($diasRestantes < 0) {
        return [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-tools',
            'score' => 0,
            'status' => 'Vencido',
            'detail' => 'Venció hace ' . abs($diasRestantes) . ' día(s)',
            'class' => 'bg-danger',
            'badge' => 'badge-danger',
        ];
    }

    if ($diasRestantes <= 30) {
        return [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-tools',
            'score' => $score,
            'status' => 'Próximo',
            'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
            'class' => 'bg-warning',
            'badge' => 'badge-warning',
        ];
    }

    return [
        'label' => 'Mantenimiento preventivo',
        'icon' => 'fa-tools',
        'score' => $score,
        'status' => 'Al día',
        'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
        'class' => 'bg-success',
        'badge' => 'badge-success',
    ];
}

private function indicadorSeguro(): array
{
    if (!$this->documentacion || !$this->documentacion->vigencia_seguro) {
        return [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay vigencia de seguro registrada',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    }

    $vigencia = Carbon::parse($this->documentacion->vigencia_seguro);
    $diasRestantes = Carbon::now()->diffInDays($vigencia, false);

    $score = max(0, min(100, round(($diasRestantes / 365) * 100)));

    if ($diasRestantes < 0) {
        return [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => 0,
            'status' => 'Vencido',
            'detail' => 'Venció hace ' . abs($diasRestantes) . ' día(s)',
            'class' => 'bg-danger',
            'badge' => 'badge-danger',
        ];
    }

    if ($diasRestantes <= 30) {
        return [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => $score,
            'status' => 'Por vencer',
            'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
            'class' => 'bg-warning',
            'badge' => 'badge-warning',
        ];
    }

    return [
        'label' => 'Seguro vehicular',
        'icon' => 'fa-shield-alt',
        'score' => $score,
        'status' => 'Vigente',
        'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
        'class' => 'bg-success',
        'badge' => 'badge-success',
    ];
}

private function indicadorVidaUtil(): array
{
    if (!$this->fecha_adquisicion || !$this->vida_util_estimada || $this->vida_util_estimada <= 0) {
        return [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay fecha de adquisición o vida útil registrada',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    }

    $fechaAdquisicion = Carbon::parse($this->fecha_adquisicion);
    $finVidaUtil = $fechaAdquisicion->copy()->addMonths($this->vida_util_estimada);
    $hoy = Carbon::now();

    $mesesTotales = max($this->vida_util_estimada, 1);
    $mesesRestantes = $hoy->diffInMonths($finVidaUtil, false);

    $score = max(0, min(100, round(($mesesRestantes / $mesesTotales) * 100)));

    if ($mesesRestantes < 0) {
        return [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => 0,
            'status' => 'Agotada',
            'detail' => 'Superó su vida útil estimada',
            'class' => 'bg-danger',
            'badge' => 'badge-danger',
        ];
    }

    if ($mesesRestantes <= 6) {
        return [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => $score,
            'status' => 'Finalizando',
            'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es)',
            'class' => 'bg-warning',
            'badge' => 'badge-warning',
        ];
    }

    return [
        'label' => 'Vida útil del activo',
        'icon' => 'fa-chart-line',
        'score' => $score,
        'status' => 'Vigente',
        'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es)',
        'class' => 'bg-success',
        'badge' => 'badge-success',
    ];
}
    
    public function registrarMantenimiento(Vehiculo $vehiculo)
    {
        $vehiculo->update([
            'fecha_ultimo_mantenimiento' => now()->toDateString(),
        ]);

        return redirect()
            ->route('vehiculos.index')
            ->with('success', 'Mantenimiento registrado correctamente para el vehículo.');
    }

    public function indexaddwork(Vehiculo $vehiculo)
    {
        return view('vehiculos.addworkVehicle', compact('vehiculo'));
    }

    public function saveWork(Request $request, Vehiculo $vehiculo)
{
    $validated = $request->validate([
        'tipo_evento'       => 'required|string|max:255',
        'tipo_evento_input' => 'nullable|string|max:255',
        'proveedor'         => 'nullable|string|max:255',
        'usuario_id'        => 'required|exists:users,id',
        'kilometraje'       => 'required|integer|min:0',
        'fecha_evento'      => 'required|date',
        'contexto'          => 'required|string',
        'costo'             => 'nullable|numeric|min:0',
        // Ambos archivos son opcionales (el usuario puede no tenerlos)
        'orden_servicio'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'factura'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    $tipoEventoFinal = $validated['tipo_evento'];
    if ($tipoEventoFinal === 'OTRO_VALOR' && !empty($validated['tipo_evento_input'])) {
        $tipoEventoFinal = $validated['tipo_evento_input'];
    }

    // Subimos los archivos (si vinieron) al disco público
    $ordenServicioPath = null;
    if ($request->hasFile('orden_servicio')) {
        $ordenServicioPath = $request->file('orden_servicio')
            ->store('mantenimientos/ordenes_servicio', 'public');
    }

    $facturaPath = null;
    if ($request->hasFile('factura')) {
        $facturaPath = $request->file('factura')
            ->store('mantenimientos/facturas', 'public');
    }

    MantenimientoVehiculo::create([
        'vehiculo_id'          => $vehiculo->id,
        'usuario_id'           => $validated['usuario_id'],
        'tipo_evento'          => $tipoEventoFinal,
        'proveedor'            => $validated['proveedor'] ?? null,
        'kilometraje'          => $validated['kilometraje'],
        'fecha_evento'         => $validated['fecha_evento'],
        'contexto'             => $validated['contexto'],
        'costo'                => $validated['costo'],
        'orden_servicio_path'  => $ordenServicioPath,
        'factura_path'         => $facturaPath,
    ]);

    // Mantenemos actualizada la fecha de último mantenimiento del vehículo
    $vehiculo->update(['fecha_ultimo_mantenimiento' => $validated['fecha_evento']]);

    return redirect()->route('vehiculos.index')
        ->with('success', 'Mantenimiento registrado correctamente en la bitácora del vehículo.');
}

    public function adjuntarArchivos(Request $request, MantenimientoVehiculo $mantenimiento)
    {
        $validated = $request->validate([
            'orden_servicio' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'factura'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('orden_servicio')) {
            if ($mantenimiento->orden_servicio_path) {
                Storage::disk('public')->delete($mantenimiento->orden_servicio_path);
            }

            $mantenimiento->orden_servicio_path = $request->file('orden_servicio')
                ->store('mantenimientos/ordenes_servicio', 'public');
        }

        if ($request->hasFile('factura')) {
            if ($mantenimiento->factura_path) {
                Storage::disk('public')->delete($mantenimiento->factura_path);
            }

            $mantenimiento->factura_path = $request->file('factura')
                ->store('mantenimientos/facturas', 'public');
        }

        $mantenimiento->save();

        return redirect()
            ->route('vehiculos.show', $mantenimiento->vehiculo_id)
            ->with('success', 'Documento adjuntado correctamente al mantenimiento.');
    }

    public function verArchivo(MantenimientoVehiculo $mantenimiento, string $tipo)
    {
        $campo = $this->campoArchivo($tipo);

        if (!$campo || !$mantenimiento->{$campo} || !Storage::disk('public')->exists($mantenimiento->{$campo})) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('public')->response($mantenimiento->{$campo});
    }

    public function descargarArchivo(MantenimientoVehiculo $mantenimiento, string $tipo)
    {
        $campo = $this->campoArchivo($tipo);

        if (!$campo || !$mantenimiento->{$campo} || !Storage::disk('public')->exists($mantenimiento->{$campo})) {
            abort(404, 'Archivo no encontrado.');
        }

        return Storage::disk('public')->download($mantenimiento->{$campo});
    }

    private function campoArchivo(string $tipo): ?string
    {
        return match ($tipo) {
            'orden_servicio' => 'orden_servicio_path',
            'factura'        => 'factura_path',
            default          => null,
        };
    }

}