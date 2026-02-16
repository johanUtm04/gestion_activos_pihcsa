<?php

namespace App\Http\Controllers;
use App\Models\TipoActivo;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Controlador destinado para manejar CRUD(CREATE, READ, UPDATE, DELETE) de  Catalogo de Tipos de Activo
|--------------------------------------------------------------------------
*/

class TipoActivoController extends Controller
{
    //Metodo para mostrar vista
    public function index()
    {
	$tipo_activo= TipoActivo::paginate(10);
        return view('tipo_activos.index', compact('tipo_activo'));
    }

    //Metodo para cargar formulario de creacion
    public function create()
    {
        return view('tipo_activos.create');
    }

    //Metodo para crear registro en Base de datos
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:marcas|max:255']);
        TipoActivo::create($request->all());
        return redirect()->route('tipo_activos.index')->with('success', 'Tipo de activo agregado al catalogo correctamente');
    }

    public function show(string $id)
    {
        //
    }

    //Metodo para cargar formulario de edicion
    public function edit(TipoActivo $tipo_activo)
    {
        return view('tipo_activos.edit', compact('tipo_activo'));
    }

    //Metodo para editar registro en Base de datos
    public function update(Request $request, TipoActivo $tipo_activo)
    {
        $request->validate(['nombre' => 'required|max:255|unique:marcas,nombre,' . $tipo_activo->id]);
        $tipo_activo->update($request->all());
        return redirect()->route('tipo_activos.index')->with('success', 'Tipo de activo actualizado en el catalogo correctamente');
    }

    //Metodo para eliminar registro de base de datos
    public function destroy(TipoActivo $tipo_activo)
    {
        if($tipo_activo->equipos()->count() > 0) {
            return redirect()->route('marcas.index')->with('danger', 'No se puede eliminar: esta marca tiene equipos asociados.');
        }
        $tipo_activo->delete();
        return redirect()->route('marcas.index')->with('danger', 'Tipo de activo eliminado correctamente');
    }
}
