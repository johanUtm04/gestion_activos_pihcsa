<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Equipo;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    const PER_PAGE = 10;

    public function index(Request $request)
    {
        $query = Departamento::query();

        if ($request->filled('departamento_nombre')) {
            $query->where('nombre', $request->departamento_nombre);
        }

        $departamentos = $query->orderBy('nombre', 'asc')->paginate(self::PER_PAGE)->withQueryString();

        $todosLosDepartamentos = Departamento::orderBy('nombre', 'asc')->get();

        return view('departamentos.index', compact('departamentos', 'todosLosDepartamentos'));
    }

    public function create()
    {
        return view('departamentos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
        ]);

        $data['nombre'] = strtoupper($data['nombre']);

        $departamento = Departamento::create($data);

        return redirect()->route('departamentos.index', ['page' => $this->getReturnPage($departamento->id)])
            ->with('new_id', $departamento->id)
            ->with('success', 'Departamento creado correctamente.');
    }

    public function edit(Departamento $departamento)
    {
        return view('departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre,' . $departamento->id,
        ]);

        $data['nombre'] = strtoupper($data['nombre']);

        $departamento->update($data);

        return redirect()->route('departamentos.index', ['page' => $this->getReturnPage($departamento->id)])
            ->with('actualizado_id', $departamento->id)
            ->with('warning', 'Departamento actualizado correctamente');
    }

    public function destroy(Departamento $departamento)
    {
        if ($departamento->equiposCount() > 0) {
            return redirect()
                ->back()
                ->with('danger', 'No se puede eliminar: hay equipos asignados a este departamento.');
        }

        $page = $this->getReturnPage($departamento->id);

        $departamento->delete();

        return redirect()
            ->route('departamentos.index', ['page' => $page])
            ->with('danger', 'Departamento eliminado.');
    }

    private function getReturnPage($departamentoId)
    {
        $target = Departamento::find($departamentoId);
        if (!$target) return 1;

        $position = Departamento::where('nombre', '<=', $target->nombre)->count();
        return ceil($position / self::PER_PAGE);
    }
}