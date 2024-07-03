<?php

namespace App\Http\Controllers\API;

use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Master\Containers;
use App\Http\Controllers\Controller;
use App\Models\Containers\Movements;
use Illuminate\Support\Facades\Response;
use App\Models\Booking\BookingContainerDetails;

class DententionController extends Controller
{
    //
    public function getBlnoToBookingNo(Request $request)
    {
        $blNo = BlDraft::where('company_id', $request->company_id)
            ->where('booking_id', $request->id)
            ->whereNotNull('ref_no')
            ->select('ref_no')
            ->distinct('ref_no')
            ->pluck('ref_no');
            
        return Response::json([
            'blNo' => $blNo,
        ], 200);
        
    }

    public function getBlContainers(Request $request)
    {
        $containersBooking = BookingContainerDetails::where('booking_id', $request->booking_no)
                                                ->distinct()
                                                ->pluck('container_id')->toarray();
        $mov = Movements::where('company_id', $request->company_id)
                            ->where('booking_no', $request->booking_no)
                            ->distinct()->pluck('container_id')->toarray();
                            
        $containers = Containers::select('id', 'code');
        
        $containersMov = $containers->whereIn('id', $mov)->get();
        $missingContainers = $containers->whereIn('id',  array_diff($containersBooking, $mov))->pluck('code')->toarray();

        return [
            'containersMov' => $containersMov,
            'missingContainers' => $missingContainers
        ];
    }

}