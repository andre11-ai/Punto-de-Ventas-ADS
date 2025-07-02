<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!in_array(auth()->user()->rol, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        return $next($request);
    }
}
