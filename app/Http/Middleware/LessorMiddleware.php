<?php

namespace App\Http\Middleware;

use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use Closure;

class LessorMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $lessor_id = auth()->user()->lessor_id;

            if (!empty($lessor_id)) {
                // Convert the comma-separated string to an array
                $lessor_id = rtrim($lessor_id, ', ');
                $lessorArray = explode(',', $lessor_id);

                Movements::addGlobalScope('lessor', function ($builder) use ($lessorArray) {
                    $builder->whereHas('container', function ($q) use ($lessorArray) {
                        $q->whereIn('description', $lessorArray);
                    });
                });
                Containers::addGlobalScope('lessor', function ($builder) use ($lessorArray) {
                    $builder->whereIn('description', $lessorArray);
                });
            }
        }
        return $next($request);
    }
}
