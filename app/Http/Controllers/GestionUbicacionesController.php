<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class GestionUbicacionesController extends Controller
{
    // Centralizamos el paginado. 
    // Si mañana quieres mostrar 10, solo cambias este número.
    const PER_PAGE = 3;

    public function index()
    {
        $ubicaciones = Ubicacion::paginate(self::PER_PAGE);
        return view('ubicaciones.index', compact('ubicaciones'));
    }

    public function create()
    {
        return view('ubicaciones.create');
    }

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

    public function edit(Ubicacion $ubicacion)
    {
        return view('ubicaciones.edit', compact('ubicacion'));
    }

    public function update(Request $request, Ubicacion $ubicacion)
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'codigo' => 'nullable|string|max:255|unique:ubicaciones,codigo,' . $ubicacion->id,
        ]);

        $ubicacion->update($data);

        return redirect()->route('ubicaciones.index', ['page' => $this->getReturnPage($ubicacion->id)])
            ->with('warning', 'Ubicación editada correctamente')
            ->with('actualizado_id', $ubicacion->id);
    }

    public function destroy(Ubicacion $ubicacion)
    {
        // 1. Calculamos la página ANTES de eliminarlo de la base de datos
        $page = $this->getReturnPage($ubicacion->id);
        
        $ubicacion->delete();

        return redirect()->route('ubicaciones.index', ['page' => $page])
            ->with('danger', 'Ubicación eliminada correctamente');
    }

    /**
     * LÓGICA REUTILIZABLE: Calcula la página destino basada en el ID
     */
    private function getReturnPage($id)
    {
        $position = Ubicacion::where('id', '<=', $id)->count();
        return ceil($position / self::PER_PAGE);
    }
}