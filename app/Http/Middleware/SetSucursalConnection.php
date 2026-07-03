<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetSucursalConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        $sucursal = $request->session()->get('sucursal_activa');

        if (! $sucursal) {
            return $next($request);
        }

        $sucursalesPermitidas = array_keys(config('sucursales.disponibles', []));

        if (
            ! in_array($sucursal, $sucursalesPermitidas, true) ||
            ! config("database.connections.$sucursal")
        ) {
            abort(403, 'Invalid or unconfigured branch.');
        }

        config(['database.default' => $sucursal]);

        DB::setDefaultConnection($sucursal);
        DB::purge($sucursal);
        DB::reconnect($sucursal);

        return $next($request);
    }
}