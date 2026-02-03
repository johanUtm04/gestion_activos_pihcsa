<?php

namespace App\Http\Controllers;
use App\Models\Equipo;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Controlador destinado para acciones depreciacion
|--------------------------------------------------------------------------
*/

class DepreciacionController extends Controller
{

//Metodo para mostrar vista
public function index(Request $request)
{
    $equipos = Equipo::paginate(10);
        return view('depreciacion.index', compact('equipos'));
    }
    
}
