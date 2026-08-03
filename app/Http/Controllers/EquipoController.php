<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\{
    Equipo,
    Ubicacion,
    User,
    Monitor,
    DiscoDuro,
    Ram,
    Procesador,
    Marca,
    TipoActivo,
    Departamento,
};

use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\StoreWorkRequest;
use App\Services\MantenimientoService;
use App\Services\ExportService;
use App\Services\ExportHistoricoService;
use App\Http\Requests\UpdateEquipoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



class EquipoController extends Controller
{
    public function index(Request $request)
    {
        session()->forget('wizard_equipo');

        $equipos = Equipo::with([
                'usuario',
                'ubicacion',
                'marca',
                'tipoActivo',
                'procesadores',
                'rams',
                'discosDuros'
            ])
            ->filtrar($request->all())
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        $data = $this->getFilterData();

        return view('equipos.index', compact('equipos'))->with($data);
    }

    public function store(StoreEquipoRequest $request)
    {
        $uuid = Str::uuid()->toString();

        session()->put('wizard_equipo', [
            'uuid'   => $uuid,
            'equipo' => $request->validatedData()
        ]);

        return redirect()->route('equipos.wizard.ubicacion', $uuid);
    }

    public function edit(Equipo $equipo)
    {
        $equipo->load(['usuario', 'ubicacion', 'marca', 'tipoActivo']);
        $catalogos = $this->getFilterData();

        return view('equipos.edit', compact('equipo'))->with($catalogos);
    }

    public function update(UpdateEquipoRequest $request, Equipo $equipo)
    {
        $data = $request->getProcessedData();

        DB::beginTransaction();

        $equipo->update($data);

        try {
            $equipo->update($request->getProcessedData());

            $this->syncRelation($equipo->perifericos(),  $request->input('periferico', []));
            $this->syncRelation($equipo->rams(),         $request->input('ram', []));
            $this->syncRelation($equipo->procesadores(), $request->input('procesador', []));
            $this->syncRelation($equipo->monitores(),    $request->input('monitor', []));
            $this->syncRelation($equipo->discosDuros(),  $request->input('discoDuro', []));

            DB::commit();

            $perPage = 10;
            $position = Equipo::where('id', '<=', $equipo->id)->count();
            $page = ceil($position / $perPage);

            return redirect()
                ->route('equipos.index', ['page' => $page])
                ->with('warning', 'Equipo actualizado')
                ->with('actualizado_id', $equipo->id);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error actualizando equipo {$equipo->id}: " . $e->getMessage());

            return back()->with(
                'error',
                'Ocurrió un error técnico al guardar. Los cambios no se aplicaron.'
            );
        }
    }

    public function show(Equipo $equipo) 
    {
        $equipo->load([
            'usuario', 
            'ubicacion', 
            'marca',
            'tipoActivo',
            'monitores', 
            'discosDuros', 
            'rams', 
            'perifericos', 
            'procesadores'
        ]);

        $catalogos = $this->getFilterData();
        return view('equipos.detalles', compact('equipo'))->with($catalogos);
    }

    public function destroy(Request $request, Equipo $equipo)
    {
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / 10);

        if (!$request->motivo || trim($request->motivo) === '') {
            return back()->with('error', 'El motivo de inactivación es obligatorio.');
        }

        $equipo->update([
            'motivo_inactivacion' => $request->motivo
        ]);

        $equipo->delete();

        return redirect()
            ->route('equipos.index', ['page' => $page])
            ->with('danger', 'Equipo inactivado correctamente.');
    }

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

            $data = collect($item)
                ->forget(['id', '_delete'])
                ->toArray();

            $data['is_active'] = isset($item['is_active']) ? 1 : 0;

            $motivoActual = $item['motivo_inactivo'] ?? 'Sin motivo';

            if (!$data['is_active'] && strpos($motivoActual, '|') === false) {
                $data['motivo_inactivo'] = $motivoActual . ' | ' . date('d/m/Y');
            } else {
                $data['motivo_inactivo'] = $data['is_active'] ? null : $motivoActual;
            }

            $relation->updateOrCreate(['id' => $id], $data);
        }
    }

    public function indexaddwork(Equipo $equipo)
    {
        $equipo->load(['marca', 'tipoActivo', 'ubicacion']);

        $catalogos = $this->getFilterData();

        return view('equipos.addwork', compact('equipo'))->with($catalogos);
    }

    public function saveWork(Equipo $equipo, StoreWorkRequest $request, MantenimientoService $service)
    {
        $data = $request->getCleanData();

        if ($data['tipo_evento'] === 'Mantenimiento mensual') {
            $equipo->update(['fecha_ultimo_mantenimiento' => $data['fecha_evento']]);
        }

        $service->registrar($equipo, $data);

        $perPage = 10;
        $position = Equipo::where('id', '<=', $equipo->id)->count();
        $page = ceil($position / $perPage);

        return redirect()
            ->route('equipos.index', ['page' => $page])
            ->with('secondary', 'Mantenimiento registrado')
            ->with('new_mantenimiento', $equipo->id);
    }

    public function exportarGeneral(ExportService $exportService)
    {
        [$callback, $headers] = $exportService->exportarInventarioCsv();
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function indexFactura(Equipo $equipo)
    {
        return view('equipos.factura.edit', compact('equipo'));
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

        return redirect()
            ->route('equipos.index', ['page' => $page])
            ->with(
                'success',
                "<strong>Mantenimiento Registrado:</strong> Se agregó una nueva orden de trabajo para el activo {$equipo->serial}."
            )
            ->with('actualizado_factura', $equipo->id);
    }

    public function ticket($id)
    {
        $equipo = \App\Models\Equipo::findOrFail($id);
        return view('equipos.ticket', compact('equipo'));
    }

    public function vistaBusqueda(){
        return view ('equipos.busqueda');
    }

    public function procesar(Request $request)
    {
        $serial = trim($request->serial);

        $equipo = Equipo::where('serial', $serial)
                        ->orWhere('id', $serial) 
                        ->first();

        if ($equipo) {
            return redirect()->route('equipos.show', $equipo->id);
        }

        return back()->with('error', 'El Activo con serial [' . $serial . '] no existe en la base de datos.');
    }
    private function getFilterData()
    {
        return [
            'ubicaciones' => Ubicacion::all(),
            'usuarios'    => User::all(),
            'marcas'      => Marca::all(),
            'tipos'       => TipoActivo::all(),
            'usuarios'    => User::all(),
            'ubicaciones' => Ubicacion::all(),
            'marcas'      => Marca::all(),
            'tiposActivo' => TipoActivo::all(),
            'departamentos' => Departamento::all(),

            'marcas_monitores'  => Monitor::distinct()->orderBy('marca', 'asc')->pluck('marca'),
            'escalas_pulgadas'  => Monitor::distinct()->orderBy('escala_pulgadas', 'asc')->pluck('escala_pulgadas'),
            'monitor_interface' => Monitor::distinct()->orderBy('interface', 'asc')->pluck('interface'),

            'discos_capacidades' => DiscoDuro::distinct()->orderBy('capacidad', 'asc')->pluck('capacidad'),
            'discos_tipos'       => DiscoDuro::distinct()->pluck('tipo_hdd_ssd'),
            'discos_interfaces'  => DiscoDuro::distinct()->pluck('interface'),

            'rams_capacidades' => Ram::distinct()->orderBy('capacidad_gb', 'asc')->pluck('capacidad_gb'),
            'rams_clocks'      => Ram::distinct()->orderBy('clock_mhz', 'asc')->pluck('clock_mhz'),
            'rams_tipos'       => Ram::distinct()->orderBy('tipo_chz', 'asc')->pluck('tipo_chz'),

            'procesador_marcas' => Procesador::distinct()->orderBy('marca', 'asc')->pluck('marca'),
            'procesador_tipos'  => Procesador::distinct()->orderBy('descripcion_tipo', 'asc')->pluck('descripcion_tipo'),
            'procesador_ghz' => Procesador::whereNotNull('clock_ghz')->distinct()->orderBy('clock_ghz', 'asc')->pluck('clock_ghz'),
        ];
    }

    public function exportarHistorico(ExportHistoricoService $exportService)
    {
        [$callback, $headers] = $exportService->exportarHistoricoCsv();

        return response()->stream($callback, 200, $headers);
    }
}