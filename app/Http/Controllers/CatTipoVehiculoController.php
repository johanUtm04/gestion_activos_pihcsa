<?php

namespace App\Http\Controllers;

use App\Models\CatTipoVehiculo;
use Illuminate\Http\Request;

class CatTipoVehiculoController extends Controller
{
    const PER_PAGE = 10;

    public function index(Request $request)
    {
        $query = CatTipoVehiculo::query()->withCount('vehiculos');

        // Filtro de búsqueda por nombre
        if ($request->filled('tipo_nombre')) {
            $query->where('nombre', $request->tipo_nombre);
        }

        $tipos_vehiculo = $query->orderBy('id', 'asc')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // Para poblar selectores o páneles de filtros avanzados
        $todosLosTipos = CatTipoVehiculo::orderBy('nombre', 'asc')->get();

        return view('tipo_vehiculos.index', compact('tipos_vehiculo', 'todosLosTipos'));
    }

    public function create()
    {
        return view('tipo_vehiculos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:cat_tipo_vehiculos,nombre',
            // Puedes añadir frecuencia de mantenimiento si aplica para vehículos
            'frecuencia_meses' => 'nullable|integer|min:0|max:48' 
        ]);

        $tipo = CatTipoVehiculo::create($data);

        return redirect()->route('tipo_vehiculos.index', ['page' => $this->getReturnPage($tipo->id)])
            ->with('new_id', $tipo->id)
            ->with('success', 'Tipo de vehículo agregado al catálogo correctamente');
    }

    public function edit($id)
    {
        $tipo_vehiculo = CatTipoVehiculo::findOrFail($id);
        return view('tipo_vehiculos.edit', compact('tipo_vehiculo'));
    }

    public function update(Request $request, $id)
    {
        $tipo_vehiculo = CatTipoVehiculo::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:cat_tipo_vehiculos,nombre,' . $tipo_vehiculo->id,
            'frecuencia_meses' => 'nullable|integer|min:0|max:48'
        ]);

        $tipo_vehiculo->update($data);

        return redirect()->route('tipo_vehiculos.index', ['page' => $this->getReturnPage($tipo_vehiculo->id)])
            ->with('actualizado_id', $tipo_vehiculo->id)
            ->with('warning', 'Tipo de vehículo actualizado en el catálogo correctamente');
    }

    public function destroy($id)
    {
        $tipo_vehiculo = CatTipoVehiculo::findOrFail($id);

        // Restricción de integridad: Evita dejar vehículos huérfanos
        if ($tipo_vehiculo->vehiculos()->count() > 0) {
            return redirect()->back()->with('danger', 'No se puede eliminar: existen vehículos asociados a esta categoría.');
        }

        $page = $this->getReturnPage($tipo_vehiculo->id);
        $tipo_vehiculo->delete();

        return redirect()->route('tipo_vehiculos.index', ['page' => $page])
            ->with('danger', 'Tipo de vehículo eliminado correctamente');
    }

    /**
     * Calcula la página exacta a la que debe retornar el flujo
     */
    private function getReturnPage($tipoId)
    {
        $target = CatTipoVehiculo::find($tipoId);
        if (!$target) return 1;

        $position = CatTipoVehiculo::where('id', '<=', $tipoId)->count();
        return ceil($position / self::PER_PAGE);
    }
}