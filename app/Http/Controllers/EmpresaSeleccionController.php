<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaSeleccionController extends Controller
{
    // 1. Mostrar la vista con el select de empresas
    public function mostrarSelector()
    {
        // Traemos solo las empresas activas
        $empresas = Empresa::where('activo', true)->get();
        return view('empresas.seleccionar', compact('empresas'));
    }

    // 2. Procesar el formulario e inyectar en la sesión
    public function guardarSeleccion(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id'
        ]);

        // Guardamos el ID en la sesión global
        session(['empresa_id' => $request->empresa_id]);

        // Redireccionamos a tu ruta principal de vehículos
        return redirect()->route('vehiculos.index')
                         ->with('success', 'Entidad seleccionada correctamente.');
    }
}