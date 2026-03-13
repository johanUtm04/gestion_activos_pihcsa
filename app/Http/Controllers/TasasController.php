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
        dd($tasas->id);
        return view('tasas.index', compact('tasas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'descripcion' => 'nullable|string'
        ]);

        Tasa::create([
            'nombre' => $request->nombre,
            'porcentaje' => $request->porcentaje,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('tasas.index')->with('success', 'Tasa registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'porcentaje' => 'required|numeric',
        ]);

        $tasa = Tasa::findOrFail($id);
        $tasa->update($request->all());

        return redirect()->route('tasas.index')->with('actualizado', 'La tasa se actualizó correctamente.');
    }

    public function destroy($id)
    {
        $tasa = Tasa::findOrFail($id);
        $tasa->delete();

        return redirect()->route('tasas.index')->with('success', 'Tasa eliminada correctamente.');
    }
}
