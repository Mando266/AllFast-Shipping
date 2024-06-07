<?php

namespace App\Http\Middleware;

use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use Closure;

class ContainerOwnerShipTypeMiddleware
{

    public function handle($request, Closure $next)
    {

    if (auth()->check()) {
        $containerOwnershipTypes = auth()->user()->container_ownership_type;

        if (!empty($containerOwnershipTypes)) {
            // Convert the comma-separated string to an array
            $containerOwnershipTypes = rtrim($containerOwnershipTypes, ', ');
            $containerOwnershipTypesArray = explode(',', $containerOwnershipTypes);
            
            Movements::addGlobalScope('containerOwnerShipType', function ($builder) use ($containerOwnershipTypesArray) {
                $builder->whereHas('container', function ($q) use ($containerOwnershipTypesArray) {
                    $q->whereIn('container_ownership_id', $containerOwnershipTypesArray);
                });
            });
            Containers::addGlobalScope('containerOwnerShipType', function ($builder) use ($containerOwnershipTypesArray) {
                $builder->whereIn('container_ownership_id', $containerOwnershipTypesArray);
            });
        }
    }

        return $next($request);
    }
}
