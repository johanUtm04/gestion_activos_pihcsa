<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historial_log;
use App\Models\Equipo;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        //eager loading  (Cargar las relaciones)
        $query = Historial_log::with(['equipo', 'usuario']);

        #Viene algo de la seccion de 
        if ($request->filled('tipo_registro')) {
        $query->where('tipo_registro', $request->tipo_registro);
        }

        // Filtro: Tipo de Activo (Este es un filtro "especial")
        // Como el tipo de activo vive en la tabla EQUIPOS, usamos whereHas = relaciones que complen estos
        if ($request->filled('tipo_equipo')) {
                $query->whereHas('equipo', function($q) use ($request) {
                    $q->where('tipo_equipo', $request->tipo_equipo);
                });
            }

        $ubicaciones = \App\Models\Ubicacion::all();
        $equipos = Equipo::with(['historials', 'usuario', 'ubicacion'])->paginate(10);

        // Cargamos los equipos con sus historiales y los usuarios de esos historiales de un solo golpe
        // $equipos = Equipo::with(['historials.usuario'])->get();
        return view('historial.index', compact('ubicaciones', 'equipos'));
    }
}
