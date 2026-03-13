<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tasa;

class TasasController extends Controller
{
    public function index(Request $request)
    {
        $tasas = Tasa::all();
        return view('tasas.index', compact('tasas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $tasa = Tasa::create($request->all());

        
        return redirect()->route('tasas.index')
        ->with('success', 'Tasa registrada correctamente.')
        ->with('tasa_id', $tasa->id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'porcentaje' => 'required|numeric',
        ]);

        $tasa = Tasa::findOrFail($id);
        $tasa->update($request->all());

        return redirect()->route('tasas.index')->with('actualizado', 'La tasa se actualizó correctamente.')
        ->with('tasa_id', $id);;
    }

    public function destroy($id)
    {
        $tasa = Tasa::findOrFail($id);
        $tasa->delete();

        return redirect()->route('tasas.index')->with('success', 'Tasa eliminada correctamente.');
    }
}
