<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historial_log;
use App\Models\Equipo;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Controlador destinado para ver registros del historial
|--------------------------------------------------------------------------
*/

class HistorialController extends Controller
{
    //Metodo para mostrar vista
    public function index(Request $request)
    {
        $listaParaFiltro = \App\Models\Equipo::select('id')->orderBy('id', 'asc')->get();
        $usuarios = User::orderBy('name')->get();
        $query = Historial_log::with(['equipo', 'usuario']);
        $ubicaciones = \App\Models\Ubicacion::all();
        $equipos = \App\Models\Equipo::with(['historials.usuario', 'usuario', 'tipoActivo'])
        ->when($request->usuario_id, function ($query) use ($request) {
            $query->where('usuario_id', $request->usuario_id);
        })
        ->when($request->equipo_id, function ($query) use ($request) {
            $query->where('id', $request->equipo_id);
        })
        ->latest()
        ->paginate(10);


        if ($request->filled('tipo_registro')) {
        $query->where('tipo_registro', $request->tipo_registro);
        }

        if ($request->filled('tipo_equipo')) {
                $query->whereHas('equipo', function($q) use ($request) {
                    $q->where('tipo_equipo', $request->tipo_equipo);
                });
            }

        return view('historial.index', compact('ubicaciones', 'equipos', 'usuarios', 'listaParaFiltro'));
    }
}
