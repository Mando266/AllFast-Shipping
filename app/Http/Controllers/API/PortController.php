<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Master\Ports;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function getPorts(Request $request)
{
    $voyageId = $request->input('id');

    $ports = Ports::whereHas('voyagePorts',fn($q) => $q->where('voyage_id',$voyageId))
        ->get('id')->pluck('id');
                
    return response()->json([
        'ports' => $ports
    ]);
}
}
