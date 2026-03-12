<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inpc; 

class InpcController extends Controller
{
    public function index(Request $request)
    {
        $todosLosInpc = Inpc::orderBy('anio', 'desc')->orderBy('mes', 'asc')->get();

        $inpc_agrupado = [];
        foreach ($todosLosInpc as $registro) {
            $inpc_agrupado[$registro->anio][$registro->mes] = $registro->valor;
        }

        return view('inpc.index', compact('inpc_agrupado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anio'  => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'mes'   => 'required|integer|min:1|max:12',
            'valor' => 'required|numeric|min:0'
        ]);

        Inpc::updateOrCreate(
            ['anio' => $request->anio, 'mes' => $request->mes],
            ['valor' => $request->valor]
        );

        return redirect()->route('inpc.index')->with('success', 'Índice INPC guardado correctamente.');
    }

    public function destroy($id)
    {
        $inpc = Inpc::findOrFail($id);
        $inpc->delete();

        return redirect()->route('inpc.index')->with('success', 'Registro eliminado.');
    }
}