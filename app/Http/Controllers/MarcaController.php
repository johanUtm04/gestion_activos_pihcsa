<?php

namespace App\Http\Controllers;
use App\Models\Marca;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Controlador destinado para manejar CRUD(CREATE, READ, UPDATE, DELETE) de Marcas
|--------------------------------------------------------------------------
*/

class MarcaController extends Controller
{
    //Metodo para mostrar vista
    public function index()
    {
    $marcas = Marca::paginate(10);
    return view('marcas.index', compact('marcas'));
    }

    //Metodo para cargar formulario de creacion
    public function create()
    {
     return view('marcas.create');
    }

    //Metodo para crear registro en Base de datos
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:marcas|max:255']);
        Marca::create($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca creada correctamente.');
    }

    public function show(string $id)
    {
        //
    }

    //Metodo para cargar formulario de edicion
    public function edit(Marca $marca)
    {
	return view('marcas.edit', compact('marca'));
    }

    //Metodo para editar registro en Base de datos
    public function update(Request $request, Marca $marca)
    {
        $request->validate(['nombre' => 'required|max:255|unique:marcas,nombre,' . $marca->id]);
        $marca->update($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca actualizada correctamente.');
    }

    //Metodo para eliminar registro de base de datos
    public function destroy(Marca $marca)
    {
        if($marca->equipos()->count() > 0) {
        return redirect()->route('marcas.index')->with('danger', 'No se puede eliminar: esta marca tiene equipos asociados.');
        }
        $marca->delete();
        return redirect()->route('marcas.index')->with('danger', 'Marca eliminada.');
    }
}
