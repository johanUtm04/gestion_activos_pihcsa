<?php

namespace App\Http\Controllers;

use App\Models\{Equipo, Ubicacion, User, Marca, TipoActivo};
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepreciacionController extends Controller
{
    public function index(Request $request): View
    {
        $equipos = Equipo::with(['usuario', 'ubicacion', 'marca', 'tipoActivo'])
            ->filtrar($request->all())
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        $data = $this->getFilterData();

        return view('depreciacion.index', compact('equipos'))->with($data);
    }

    private function getFilterData()
    {
        return [
            'usuarios'    => User::all(),
            'ubicaciones' => Ubicacion::all(),
            'marcas'      => Marca::all(),
            'tipos'       => TipoActivo::all(),
        ];
    }
}