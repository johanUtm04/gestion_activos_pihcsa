<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSucursalIsSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        if (
            $request->routeIs('sucursal.seleccionar') ||
            $request->routeIs('sucursal.guardarSeleccion') ||
            $request->routeIs('logout')
        ) {
            return $next($request);
        }

        if (! session()->has('sucursal_activa')) {
            return redirect()->route('sucursal.seleccionar');
        }

        return $next($request);
    }
}