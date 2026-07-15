<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmpresaIsSelected
{
    public function handle(Request $request, Closure $next)
    {
        // Si está logueado pero no ha elegido empresa en la sesión...
        if (auth()->check() && !session()->has('empresa_id')) {
            
            // Si la petición no va ya hacia las rutas del selector, redirige
            if (!$request->routeIs('empresa.seleccionar', 'empresa.guardar')) {
                return redirect()->route('empresa.seleccionar')
                                 ->with('error', 'Debes seleccionar una sucursal para continuar.');
            }
        }

        return $next($request);
    }
}