<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

use App\Http\Requests\StoreEquipoRequest;
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

        return view('equipos.detalles', compact('equipo'));
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
        $usuarios = User::all();
        $semaforo = $this->calcularSemaforo($equipo);

        return view('equipos.addwork', compact('equipo', 'usuarios', 'semaforo'));
    }

    public function saveWork(Equipo $equipo, Request $request)
    {
        $data = $request->validate([
            'tipo_evento'        => 'required|string',
            'tipo_evento_input'  => 'required_if:tipo_evento,OTRO_VALOR|nullable|string|max:255',
            'usuario_id'         => 'required|string',
            'fecha_evento'       => 'required|date',
            'contexto'           => 'nullable|string',
            'costo'              => 'nullable|numeric',
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
            'detalles_json'     => [
                'mensaje' => 'Nuevo Mantenimiento agregado',
                'usuario_asignado' => $historial->name ?? 'conexion mal hecha we',
                'rol' => $historial->rol ?? 'conexion mal hecha amor',
                'cambios' => [
                    'Detalles del Servicio' => [
                        'antes'   => 'N/A',
                        'despues' =>
                            "<div class='text-left'>" .
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

        return redirect()
            ->route('equipos.index', ['page' => $page])
            ->with('secondary', 'Mantenimiento registrado')
            ->with('new_mantenimiento', $equipo->id);
    }

    public function exportarGeneral()
    {
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

        $callback = function () use ($equipos) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
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
                $procInfo = $equipo->procesadores
                    ->map(fn($p) => "{$p->marca} {$p->descripcion_tipo}")
                    ->implode(' | ');

                $ramInfo = $equipo->rams
                    ->map(fn($r) => "{$r->capacidad_gb}GB ({$r->tipo_chz} {$r->clock_mhz}MHz)")
                    ->implode(' | ');

                $discoInfo = $equipo->discosDuros
                    ->map(fn($d) => "{$d->capacidad}GB ({$d->tipo_hdd_ssd} - {$d->interface})")
                    ->implode(' | ');

                $monInfo = $equipo->monitores
                    ->map(fn($m) => "{$m->marca} {$m->escala_pulgadas}\" (S/N: " . ($m->serial ?? 'N/A') . ")")
                    ->implode(' | ');

                $perifInfo = $equipo->perifericos
                    ->map(fn($p) => "{$p->tipo}: {$p->marca} (" . ($p->serial ?? 'N/A') . ")")
                    ->implode(' | ');

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

    private function calcularSemaforo($equipo)
    {
        $tipo = $equipo->tipoActivo;

        if (!$tipo || $tipo->frecuencia_meses <= 0) {
            return (object)[
                'clase' => 'badge-secondary',
                'texto' => 'N/A',
                'icono' => 'fa-ban'
            ];
        }

        $fechaBase = $equipo->fecha_ultimo_mantenimiento
            ? \Carbon\Carbon::parse($equipo->fecha_ultimo_mantenimiento)
            : \Carbon\Carbon::parse($equipo->fecha_adquisicion ?? $equipo->created_at);

        $proximo = $fechaBase->copy()->addMonths($tipo->frecuencia_meses);
        $hoy = now()->startOfDay();
        $proximo = $proximo->startOfDay();

        $diasRestantes = (int)$hoy->diffInDays($proximo, false);

        if ($hoy->gt($proximo)) {
            $atraso = abs($diasRestantes);

            return (object)[
                'clase' => 'badge-danger',
                'texto' => "VENCIDO HACE ({$atraso} d)",
                'dias'  => $diasRestantes,
                'icono' => 'fa-exclamation-triangle'
            ];
        }

        if ($diasRestantes <= 30) {
            return (object)[
                'clase' => 'badge-warning',
                'texto' => "OBLIGATORIO ({$diasRestantes} d)",
                'dias'  => $diasRestantes,
                'icono' => 'fa-clock'
            ];
        }

        return (object)[
            'clase' => 'badge-success',
            'texto' => 'EQUIPO AL DÍA',
            'dias'  => $diasRestantes,
            'icono' => 'fa-check-circle'
        ];
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
        ];
    }
}