<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    const PER_PAGE = 10;

    public function index(Request $request)
    {
        $query = Empresa::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        }

        $empresas = $query->orderBy('id', 'asc')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:empresas,nombre',
            'rfc'    => 'nullable|string|max:13|unique:empresas,rfc',
        ]);

        $empresa = Empresa::create($data);

        return redirect()->route('empresas.index', ['page' => $this->getReturnPage($empresa->id)])
            ->with('new_id', $empresa->id)
            ->with('success', 'Empresa agregada al catálogo correctamente.');
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:empresas,nombre,' . $empresa->id,
            'rfc'    => 'nullable|string|max:13|unique:empresas,rfc,' . $empresa->id,
        ]);

        $empresa->update($data);

        return redirect()->route('empresas.index', ['page' => $this->getReturnPage($empresa->id)])
            ->with('actualizado_id', $empresa->id)
            ->with('warning', 'Empresa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $page = $this->getReturnPage($empresa->id);
        $empresa->delete();

        return redirect()->route('empresas.index', ['page' => $page])
            ->with('danger', 'Empresa eliminada del catálogo correctamente.');
    }

    private function getReturnPage($empresaId)
    {
        $target = Empresa::find($empresaId);
        if (!$target) return 1;

        $position = Empresa::where('id', '<=', $empresaId)->count();
        return ceil($position / self::PER_PAGE);
    }
}