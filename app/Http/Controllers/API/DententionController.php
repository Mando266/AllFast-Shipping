<?php

namespace App\Http\Controllers\API;

use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Master\Containers;
use App\Http\Controllers\Controller;
use App\Models\Containers\Movements;
use Illuminate\Support\Facades\Response;

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
        $bl_no = $request->bl;
        $mov = Movements::where('company_id', $request->company_id);
        if (in_array('all', $bl_no)) {
            $mov->where('booking_no', $request->booking_no);
        } else {
            $mov->whereIn('bl_no', $bl_no);
        }
        $mov = $mov->distinct()->pluck('container_id')->toarray();
        $containers = Containers::select('id', 'code')->whereIn('id', $mov)->get();
        return $containers;
    }
}