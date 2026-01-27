<?php

namespace App\Http\Controllers;
use App\Models\TipoActivo;
use Illuminate\Http\Request;

class TipoActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
	$tipo_activo= TipoActivo::paginate(10);
	return view('tipo_activos.index', compact('tipo_activo'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('tipo_activos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(['nombre' => 'required|unique:marcas|max:255']);
        TipoActivo::create($request->all());
        return redirect()->route('tipo_activos.index')->with('success', 'Tipo de activo agregado al catalogo correctamente');
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
    public function edit(TipoActivo $tipo_activo)
    {
        //
        return view('tipo_activos.edit', compact('tipo_activo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoActivo $tipo_activo)
    {
    $request->validate(['nombre' => 'required|max:255|unique:marcas,nombre,' . $tipo_activo->id]);
    $tipo_activo->update($request->all());
    return redirect()->route('tipo_activos.index')->with('success', 'Tipo de activo actualizado en el catalogo correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoActivo $tipo_activo)
    {
        if($tipo_activo->equipos()->count() > 0) {
            return redirect()->route('marcas.index')->with('danger', 'No se puede eliminar: esta marca tiene equipos asociados.');
        }
        $tipo_activo->delete();
        return redirect()->route('marcas.index')->with('danger', 'Tipo de activo eliminado correctamente');
    }
}
