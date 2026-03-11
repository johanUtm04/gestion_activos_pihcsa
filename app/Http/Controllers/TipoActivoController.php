<?php

namespace App\Http\Controllers;

use App\Models\TipoActivo;
use Illuminate\Http\Request;

class TipoActivoController extends Controller
{
    const PER_PAGE = 10;

    public function index(Request $request)
    {
        $query = TipoActivo::query();

        if ($request->filled('tipo_nombre')) {
            $query->where('nombre', $request->tipo_nombre);
        }

        $tipo_activo = $query->orderBy('id', 'asc')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $todosLosTipos = TipoActivo::orderBy('nombre', 'asc')->get();

        return view('tipo_activos.index', compact('tipo_activo', 'todosLosTipos'));
    }

    public function create()
    {
        return view('tipo_activos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_activos,nombre',
            'frecuencia_meses' => 'nullable|integer|min:0|max:48'
        ]);

        $tipo = TipoActivo::create($data);

        return redirect()->route('tipo_activos.index', ['page' => $this->getReturnPage($tipo->id)])
            ->with('new_id', $tipo->id)
            ->with('success', 'Tipo de activo agregado al catálogo correctamente');
    }

    public function edit(TipoActivo $tipo_activo)
    {
        return view('tipo_activos.edit', compact('tipo_activo'));
    }

    public function update(Request $request, TipoActivo $tipo_activo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_activos,nombre,' . $tipo_activo->id,
            'frecuencia_meses' => 'nullable|integer|min:0|max:48'
        ]);

        $tipo_activo->update($data);

        return redirect()->route('tipo_activos.index', ['page' => $this->getReturnPage($tipo_activo->id)])
            ->with('actualizado_id', $tipo_activo->id)
            ->with('warning', 'Tipo de activo actualizado en el catálogo correctamente');
    }

    public function destroy(TipoActivo $tipo_activo)
    {
        if ($tipo_activo->equipos()->count() > 0) {
            return redirect()->back()->with('danger', 'No se puede eliminar: este tipo tiene equipos asociados.');
        }

        $page = $this->getReturnPage($tipo_activo->id);
        $tipo_activo->delete();

        return redirect()->route('tipo_activos.index', ['page' => $page])
            ->with('danger', 'Tipo de activo eliminado correctamente');
    }

    private function getReturnPage($tipoId)
    {
        $target = TipoActivo::find($tipoId);
        if (!$target) return 1;

        $position = TipoActivo::where('id', '<=', $tipoId)->count();
        return ceil($position / self::PER_PAGE);
    }
}