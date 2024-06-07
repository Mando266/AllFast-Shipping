<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;

class BlDraftController extends Controller
{
    //
    public function containers(BlDraft $bldraft)
    {
        $containers = $bldraft->blDetails->map(function($detail) {
            return optional($detail->container)->code;
         });

        return response()->json([
             'containers' => $containers,
             'bl_no' => $bldraft->ref_no
             ]);
    }

    public function isExportJson(BlDraft $blDraft)
    {
        $booking = $blDraft->booking;
        $quotation = $booking->quotation;
        $shipmentType = $quotation->shipment_type ?? $booking->shipment_type ?? 'export';
        
        return response()->json([
            'is_export' => strtolower($shipmentType) === 'export'
        ]);
    }
}
