<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        if($request->user()->Rol=='Admin'){
            return $next($request);
        }
        if (! $request->user() || $request->user()->Rol !== $role) {
            // Redirecciona si el usuario no tiene el rol adecuado
            return response()->view('Error', [], 403);
        }

        return $next($request);
    }
}
