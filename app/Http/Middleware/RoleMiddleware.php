<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    //Todo lo que venga despues de $next guardalo en un array llamado roles
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            abort(403);
        }

            if (!in_array(auth()->user()->rol, $roles)) {
        abort(403, "Bien amorcito no cargo no es queso ");
    }
        return $next($request);
    }
}
