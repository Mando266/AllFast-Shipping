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
    public function handelDebitInvoiceData(Request $request)
    {
        $containerDetails = [];
        $selectedCode=40;   
        foreach (json_decode($request->calculation,true) as $container) {
            $containerDetails []= 'Container No: ' .str_pad($container['container_no'], 12)
            .' To Code: ' .$container['to_code']
            .' daysCount: ' .$container['daysCount']
            .' freeTime: ' .$container['freeTime']
            .' Total: ' .$container['total']
                    ;
            }
           return http_build_query([
            'notes' => $containerDetails ?? null,
            'detentionAmount' => $request->grandTotal,
            'booking_no' => $request->booking_no,
            'selectedCode' => $selectedCode,
            'bookingDetails' => $request->calculation,
            'booking_ref' => $request->booking_ref,
            'grandTotal' => $request->grandTotal,
            ]);
    } 
      
    public function createDebitInvoiceData(Request $request)
    {

        $charges = ChargesDesc::orderBy('id')->get();
        $voyages = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();
        $totalqty = $bldraft->bookingContainerDetails->count();

        return view('invoice.invoice.create_debit',$request->all()+[
            'charges'=>$charges,
            'voyages'=>$voyages,
            'totalqty' => $totalqty,
            'bldraft' => $bldraft,
            ]);
        }
        

}