<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\View\View;

/*
|--------------------------------------------------------------------------
| DepreciacionController
|--------------------------------------------------------------------------
| Gestiona el cálculo y la visualización del valor contable de los activos
| a lo largo del tiempo.
|
*/

class DepreciacionController extends Controller
{
    /**
     * Muestra el listado de equipos con su información de depreciación.
     * * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // Se recuperan los equipos con paginación para optimizar la carga de memoria
        $equipos = Equipo::paginate(10);

        return view('depreciacion.index', compact('equipos'));
    }
}