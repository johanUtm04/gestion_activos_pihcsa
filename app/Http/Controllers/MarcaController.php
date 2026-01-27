<?php

namespace App\Http\Controllers;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $marcas = Marca::paginate(10);
    return view('marcas.index', compact('marcas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
     return view('marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
	$request->validate(['nombre' => 'required|unique:marcas|max:255']);
	Marca::create($request->all());
	return redirect()->route('marcas.index')->with('success', 'Marca creada correctamente.');
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
    public function edit(Marca $marca)
    {
        //
	return view('marcas.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
	$request->validate(['nombre' => 'required|max:255|unique:marcas,nombre,' . $marca->id]);
	$marca->update($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca actualizada correctamente.');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        if($marca->equipos()->count() > 0) {
        return redirect()->route('marcas.index')->with('danger', 'No se puede eliminar: esta marca tiene equipos asociados.');
    }
        $marca->delete();
        return redirect()->route('marcas.index')->with('danger', 'Marca eliminada.');
}
}
