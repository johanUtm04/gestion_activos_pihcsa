<?php

namespace App\Http\Controllers;
use App\Models\Equipo;
use Illuminate\Http\Request;

class DepreciacionController extends Controller
{


public function index(Request $request)
{
    $equipos = Equipo::paginate(10);
        return view('depreciacion.index', compact('equipos'));
    }
    
}
