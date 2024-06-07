<?php

namespace App\Http\Middleware;

use Closure;

class DevTools
{
    public function handle($request, Closure $next)
    {
        if (auth()->id() != 1) {
            abort(404);
        }
        return $next($request);
    }
}
