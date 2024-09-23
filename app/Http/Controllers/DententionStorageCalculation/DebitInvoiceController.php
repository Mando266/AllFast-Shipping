<?php

namespace App\Http\Controllers\DententionStorageCalculation;

use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Voyages\Voyages;
use App\Models\Invoice\ChargesDesc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DebitInvoiceController extends Controller
{
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $charges = ChargesDesc::orderBy('id')->get();
        $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();
        $qty = $bldraft->bookingContainerDetails->count();
        $voyages = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $containerDetails = [];
        $selectedCode=40;
        foreach (json_decode($request->periods,true) as $item) {
                $formattedString = str_pad($item['name'], 12) . ' ' . $item['days'] . ' Days ' . $item['total'];
                $containerDetails[] = $formattedString;
            }                
        return view('invoice.invoice.create_debit',[
            'notes' => $containerDetails ?? null,
            'qty'=>$qty,
            'detentionAmount'=>$request->grandTotal,
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,
            'charges' => $charges,
            'selectedCode' => $selectedCode,
            'bookingDetails'=>$request->calculation,
        ]);
    }

}