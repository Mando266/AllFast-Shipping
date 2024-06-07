<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Containers\Demurrage;
use App\Models\Master\Containers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class DemurrageController extends Controller
{
    /**
     * @param $portId
     * @param $from
     * @param $to
     * @param $triffType
     * @return JsonResponse
     */
    public function checkTriffOverlap ($portId,$from,$to,$triffType): JsonResponse
    {
        $valid = Demurrage::query()->where('port_id', $portId)->where('tariff_type_id', $triffType)
            ->where(function ($query) use ($from, $to) {
                $query->where(function ($query) use ($from, $to) {
                    $query->where('validity_from', '<=', $from)->where('validity_to', '>=', $from);
                })->orWhere(function ($query) use ($from, $to) {
                    $query->where('validity_from', '<=', $to)->where('validity_to', '>=', $to);
                });
            })
            ->exists();
        return response()->json([
            'valid' => $valid
        ],200);
    }
}
