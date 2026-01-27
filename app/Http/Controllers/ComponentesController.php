<?php

namespace App\Http\Controllers;

use App\Models\Disco_Duro;
use App\Models\DiscoDuro;
use App\Models\Monitor;
use App\Models\Periferico;
use App\Models\Procesador;
use App\Models\Ram;
use Illuminate\Http\Request;

class ComponentesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $equipo_id)
    {
        //RAM
        if ($request->filled('ram_capacidad')) {
            Ram::create([
                'equipo_id' => $equipo_id,
                'capacidad_gb' => $request->ram_capacidad,
                'clock_mhz' => $request->ram_clock,
                'tipo_chz' => $request->ram_tipo,
            ]);
        }
        //DISCO_DURO
        if ($request->filled('disco_capacidad')) {
            DiscoDuro::create([
                'equipo_id' => $equipo_id,
                'capacidad' => $request->disco_capacidad,
                'tipo_hdd_ssd' => $request->disco_tipo,
                'interface' => $request->disco_interface,
            ]);
        }
        //PROCESADOR
        if ($request->filled('procesador_capacidad')) {
            Procesador::create([
                'equipo_id' => $equipo_id,
                'marca' => $request->procesador_marca,
                'tipo_hdd_ssd' => $request->procesador_tipo,
                'interface' => $request->procesador_interface,
            ]);
        }
        //PERIFERICO
        if ($request->filled('perifericos')) {
            Periferico::create([
                'equipo_id' => $equipo_id,
                'tipo' => $request->tipo_periferico,
                'marca' => $request->periferico_marca,
                'serial' => $request->periferico_serial,
                'interface' => $request->periferico_interface,
            ]);
        }

        //MONITOR
        if ($request->filled('monitores')) {
            Monitor::create([
                'equipo_id' => $equipo_id,
                'marca' => $request->monitor_marca,
                'serial' => $request->monitor_serial,
                'serial' => $request->monitor_escala,
                'interface' => $request->interface_interface,
            ]);
        }

        // ... y así con los demás

        return redirect()->route('equipos.index')->with('success', 'Equipo creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
