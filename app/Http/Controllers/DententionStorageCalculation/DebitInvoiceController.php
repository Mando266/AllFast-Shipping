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
        $calculation = session('detention_invoice');
        $charges = ChargesDesc::orderBy('id')->get();
        $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();
        $totalqty = $bldraft->bookingContainerDetails->count();
        $voyages = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $containerDetails = [];
        $selectedCode=40;

        foreach (json_decode($calculation,true) as $container) {
            $containerDetails []= 'Container No: ' .str_pad($container['container_no'], 12)
            .' To Code: ' .$container['to_code']
            .' daysCount: ' .$container['daysCount']
            .' freeTime: ' .$container['freeTime']
            .' Total: ' .$container['total']
            ;
        }
        return view('invoice.invoice.create_debit',[
            'notes' => $containerDetails ?? null,
            'totalqty'=>$totalqty,
            'detentionAmount'=>$request->grandTotal,
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,
            'charges' => $charges,
            'selectedCode' => $selectedCode,
            'bookingDetails'=>$calculation,
            ]);
    }
        

}