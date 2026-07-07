<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SucursalController extends Controller
{
    public function cambiar(Request $request)
    {
        abort_unless($request->user()?->rol === 'ADMIN', 403);

        $request->validate([
            'sucursal' => ['required', 'string'],
        ]);

        $sucursal = Sucursal::activas()
            ->where('clave', $request->sucursal)
            ->first();

        if (! $sucursal) {
            throw ValidationException::withMessages([
                'sucursal' => 'La sucursal seleccionada no existe o está inactiva.',
            ]);
        }

        session()->put('sucursal_activa', $sucursal->clave);

        // Importante para vehículos: limpiar empresa seleccionada al cambiar de sucursal
        session()->forget('empresa_id');

        $this->aplicarConexionSucursal($sucursal->clave, $sucursal->database_name);

        return back()->with('status', 'Sucursal activa cambiada correctamente.');
    }

    private function aplicarConexionSucursal(string $clave, string $databaseName): void
    {
        config([
            "database.connections.$clave" => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $databaseName,
                'username' => env('DB_USERNAME', 'forge'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ],
        ]);

        config(['database.default' => $clave]);

        DB::setDefaultConnection($clave);
        DB::purge($clave);
        DB::reconnect($clave);
    }
}