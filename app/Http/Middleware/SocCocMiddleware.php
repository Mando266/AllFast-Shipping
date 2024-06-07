<?php

namespace App\Http\Middleware;

use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use Closure;

class SocCocMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $soc_coc = auth()->user()->soc_coc;
            if ($soc_coc != null) {
                Movements::addGlobalScope('container', function ($builder) use ($soc_coc) {
                    $builder->whereHas('container', function ($q) use ($soc_coc) {
                        $q->where('SOC_COC', $soc_coc);
                    });
                });
                Containers::addGlobalScope('container', function ($builder) use ($soc_coc) {
                    $builder->where('SOC_COC', $soc_coc);
                });
            }
        }
        return $next($request);
    }
}
