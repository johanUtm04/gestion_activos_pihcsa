<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\FlareClient\View;
use Symfony\Component\HttpFoundation\Response;

class goku
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->user()->email == "	
ingeniero@gmail.com"
        ) {
        return redirect('no-autorizado');
        } else {
        return $next($request);      
    }

    }
}
