<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\CatTipoVehiculo; // Ajusta el nombre exacto de tu modelo de tipos si varía
use App\Models\Marca;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    /**
     * Muestra el inventario principal.
     */
    public function index()
    {
        // Cargamos las relaciones para evitar el problema de consultas N+1 en la tabla
        $vehiculos = Vehiculo::with(['tipoVehiculo', 'marca', 'ubicacion', 'usuario'])->get();
        return view('vehiculos.index', compact('vehiculos'));
    }

    /**
     * Endpoint que retorna los catálogos para rellenar los selects vía Fetch API.
     */
    public function filtros()
    {
        return response()->json([
            'tipos'       => CatTipoVehiculo::select('id', 'nombre')->get(),
            'marcas'      => Marca::select('id', 'nombre')->get(),
            'ubicaciones' => Ubicacion::select('id', 'nombre')->get(),
            'usuarios'    => User::select('id', 'name')->get(),
        ]);
    }

    /**
     * Almacena un vehículo nuevo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_vehiculo_id' => 'required|exists:cat_tipo_vehiculos,id',
            'marca_id'         => 'required|exists:marcas,id',
            'usuario_id'       => 'required|exists:users,id',
            'ubicacion_id'     => 'required|exists:ubicaciones,id',
            'modelo'           => 'required|string|max:255',
            'anio'             => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'placas'           => 'nullable|string|max:20',
            'no_serie'         => 'nullable|string|max:50',
            'no_motor'         => 'nullable|string|max:50',
            'cilindros'        => 'nullable|integer|min:1',
            'tipo_combustible' => 'nullable|string',
            'fecha_ultimo_mantenimiento' => 'nullable|date',
        ]);

        // Por defecto entra como activo
        $validated['is_active'] = true;

        Vehiculo::create($validated);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado exitosamente.');
    }

    /**
     * Doble propósito: Si es petición AJAX, retorna JSON (para editar).
     * Si es una navegación común, retorna la vista de detalles.
     */
    public function show(Request $request, $id)
    {
        $vehiculo = Vehiculo::with(['tipoVehiculo', 'marca', 'ubicacion', 'usuario', 'documentacion'])->findOrFail($id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($vehiculo);
        }

        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Actualiza los datos del vehículo y maneja la inactivación operativa.
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'tipo_vehiculo_id' => 'required|exists:cat_tipo_vehiculos,id',
            'marca_id'         => 'required|exists:marcas,id',
            'usuario_id'       => 'required|exists:users,id',
            'ubicacion_id'     => 'required|exists:ubicaciones,id',
            'modelo'           => 'required|string|max:255',
            'anio'             => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'placas'           => 'nullable|string|max:20',
            'no_serie'         => 'nullable|string|max:50',
            'no_motor'         => 'nullable|string|max:50',
            'is_active'        => 'required|boolean',
            'motivo_inactivacion' => 'required_if:is_active,0|nullable|string|max:255',
        ]);

        // Si se vuelve a activar, limpiamos el motivo anterior de forma automática
        if ($validated['is_active'] == 1) {
            $validated['motivo_inactivacion'] = null;
        }

        $vehiculo->update($validated);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado correctamente.');
    }

    /**
     * Elimina físicamente o lógicamente el registro.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado del inventario.');
    }
}