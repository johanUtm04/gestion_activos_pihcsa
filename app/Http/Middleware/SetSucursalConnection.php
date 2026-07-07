<?php

namespace App\Http\Middleware;

use App\Models\Sucursal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetSucursalConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        $clave = $request->session()->get('sucursal_activa');

        if (! $clave) {
            return $next($request);
        }

        $sucursal = Sucursal::activas()
            ->where('clave', $clave)
            ->first();

        if (! $sucursal) {
            abort(403, 'Sucursal no válida o inactiva.');
        }

        config([
            "database.connections.$sucursal->clave" => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $sucursal->database_name,
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

        config(['database.default' => $sucursal->clave]);

        DB::setDefaultConnection($sucursal->clave);
        DB::purge($sucursal->clave);
        DB::reconnect($sucursal->clave);

        return $next($request);
    }
}