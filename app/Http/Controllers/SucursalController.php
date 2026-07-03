<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SucursalController extends Controller
{
    public function cambiar(Request $request)
    {
        abort_unless($request->user()?->rol === 'ADMIN', 403);

        $sucursalesPermitidas = array_keys(config('sucursales.disponibles'));

        $data = $request->validate([
            'sucursal' => ['required', Rule::in($sucursalesPermitidas)],
        ]);

        $sucursal = $data['sucursal'];

        session()->put('sucursal_activa', $sucursal);

        config(['database.default' => $sucursal]);

        DB::setDefaultConnection($sucursal);
        DB::purge($sucursal);
        DB::reconnect($sucursal);

        return back()->with('status', 'Sucursal activa cambiada correctamente.');
    }
}