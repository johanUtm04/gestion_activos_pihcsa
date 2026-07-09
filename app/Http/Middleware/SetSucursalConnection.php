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
                $sucursal = Sucursal::activas()
                    ->where('clave', 'principal')
                    ->first();

                if (! $sucursal) {
                    abort(403, 'No existe una sucursal principal activa configurada.');
                }

                $request->session()->put('sucursal_activa', 'principal');
            }

        $mysql = config('database.connections.mysql');

        config([
            "database.connections.$sucursal->clave" => [
                'driver' => $mysql['driver'] ?? 'mysql',
                'host' => $mysql['host'] ?? '127.0.0.1',
                'port' => $mysql['port'] ?? '3306',
                'database' => $sucursal->database_name,
                'username' => $mysql['username'],
                'password' => $mysql['password'],
                'unix_socket' => $mysql['unix_socket'] ?? '',
                'charset' => $mysql['charset'] ?? 'utf8mb4',
                'collation' => $mysql['collation'] ?? 'utf8mb4_unicode_ci',
                'prefix' => $mysql['prefix'] ?? '',
                'prefix_indexes' => $mysql['prefix_indexes'] ?? true,
                'strict' => $mysql['strict'] ?? true,
                'engine' => $mysql['engine'] ?? null,
                'options' => $mysql['options'] ?? [],
            ],
        ]);

        config(['database.default' => $sucursal->clave]);

        DB::setDefaultConnection($sucursal->clave);
        DB::purge($sucursal->clave);
        DB::reconnect($sucursal->clave);

        return $next($request);
    }
}