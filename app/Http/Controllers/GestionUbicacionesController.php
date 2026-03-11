<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Controlador destinado para manejar CRUD(CREATE, READ, UPDATE, DELETE) de Catalogo de Ubicaiones
|--------------------------------------------------------------------------
*/

class GestionUbicacionesController extends Controller
{
    const PER_PAGE = 10;

    //Metodo para mostrar vista
    public function index(Request $request)
    {
    $query = Ubicacion::query(); 

    if ($request->filled('ubicacion_id')) {
        $query->where('id', $request->ubicacion_id);
    }

    $ubicaciones = $query->orderBy('id', 'asc') 
    ->paginate(self::PER_PAGE)
    ->withQueryString(); 

    $todasLasUbicaciones = Ubicacion::orderBy('nombre', 'asc')->get();

    return view('ubicaciones.index', compact('ubicaciones', 'todasLasUbicaciones'));
    }

    //Metodo para cargar formulario de creacion
    public function create()
    {
        return view('ubicaciones.create');
    }

    //Metodo para crear registro en Base de datos
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255|unique:ubicaciones,codigo',
        ]);

        $ubicacion = Ubicacion::create($data);

        return redirect()->route('ubicaciones.index', ['page' => $this->getReturnPage($ubicacion->id)])
            ->with('success', 'Ubicación agregada correctamente')
            ->with('new_id', $ubicacion->id);
    }

    //Metodo para cargar formulario de edicion
    public function edit(Ubicacion $ubicacion)
    {
        return view('ubicaciones.edit', compact('ubicacion'));
    }

    //Metodo para editar registro en Base de datos
    public function update(Request $request, Ubicacion $ubicacion)
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'codigo' => 'nullable|string|max:255|unique:ubicaciones,codigo,' . $ubicacion->id,
        ]);

        $ubicacion->update($data);

        return redirect()->route('ubicaciones.index', ['page' => $this->getReturnPage($ubicacion->id)])
        ->with('actualizado_id', $ubicacion->id)    
        ->with('warning', 'Ubicación editada correctamente');
    }

    //Metodo para eliminar registro de base de datos
    public function destroy(Ubicacion $ubicacion)
    {
        // 1. Calculamos la página ANTES de eliminarlo de la base de datos
        $page = $this->getReturnPage($ubicacion->id);
        
        $ubicacion->delete();

        return redirect()->route('ubicaciones.index', ['page' => $page])
            ->with('danger', 'Ubicación eliminada correctamente');
    }

    //METODO HELPER: Calcula la página en la que se encuentra un registro
    private function getReturnPage($id)
    {
        $position = Ubicacion::where('id', '<=', $id)->count();
        return ceil($position / self::PER_PAGE);
    }
}