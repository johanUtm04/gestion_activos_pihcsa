<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Equipo;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    const PER_PAGE = 10;

public function index(Request $request)
{
    $query = Marca::query();

    // Aquí no usas whereHas porque ya estás en el modelo Marca
    if ($request->filled('marca_nombre')) {
        $query->where('nombre', $request->marca_nombre);
    }

    $marcas = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
    
    // Para el select del filtro
    $todasLasMarcas = Marca::orderBy('nombre', 'asc')->get();

    return view('marcas.index', compact('marcas', 'todasLasMarcas'));
}

    public function create()
    {
        return view('marcas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre',
            'tipo'   => 'required|in:TI,AUTO',
        ]);

        $marca = Marca::create($data);

        return redirect()->route('marcas.index', ['page' => $this->getReturnPage($marca->id)])
            ->with('new_id', $marca->id)
            ->with('success', 'Marca creada correctamente.');
    }

    public function edit(Marca $marca)
    {
        return view('marcas.edit', compact('marca'));
    }

    public function update(Request $request, Marca $marca)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre,' . $marca->id
        ]);

        $marca->update($data);

        return redirect()->route('marcas.index', ['page' => $this->getReturnPage($marca->id)])
            ->with('actualizado_id', $marca->id)
            ->with('warning', 'Marca actualizada correctamente.');
    }

    public function destroy(Marca $marca)
    {
        if ($marca->equipos()->count() > 0) {
            return redirect()
                ->back()
                ->with('danger', 'No se puede eliminar: esta marca tiene equipos asociados.');
        }

        if ($marca->vehiculos()->count() > 0) {
            return redirect()
                ->back()
                ->with('danger', 'No se puede eliminar: esta marca tiene vehículos asociados.');
        }

        $page = $this->getReturnPage($marca->id);

        $marca->delete();

        return redirect()
            ->route('marcas.index', ['page' => $page])
            ->with('danger', 'Marca eliminada.');
    }

    private function getReturnPage($marcaId)
    {
        $target = Marca::find($marcaId);
        if (!$target) return 1;

        $position = Marca::where('id', '<=', $marcaId)->count();
        return ceil($position / self::PER_PAGE);
    }
}