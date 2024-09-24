<?php

namespace App\Http\Controllers\DententionStorageCalculation;

use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Invoice\Invoice;
use App\Models\Voyages\Voyages;
use App\Models\Invoice\ChargesDesc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExtentionDententionController extends Controller
{
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $codes=['SNTC'];
        $charges = ChargesDesc::orderBy('id')->get();
        $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();
        $voyages = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $selectedCode=40;
        $containers=json_decode($request->data,true);
        $containers = array_filter($containers, function($container) use ($codes) {
            return in_array($container['to_code'],$codes);
        });
        $invoice = Invoice::with('invoiceBooking')->where('booking_ref',$request->booking_no)
                    ->where('type','debit')
                    ->latest('date')->first();
        
        $invoiceBooking = $invoice->invoiceBooking()->whereIn('to_code',$codes)->get();

                // $invoiceBooking =InvoiceBooking::whereHas('invoice', function ($query) {
                // $query->where('type','debit');
                // })
                // ->whereIn('to_code',$codes)
                // ->where('booking_id',$request->booking_no)
                // ->get();
        
        $containerDetails=[];
        $grandTotal=0;
        $note=[];
        foreach ($containers as $container) {
            $rem=0;
            $prevTotal= $invoiceBooking->where('container_id',$container['container_id'])->where('booking_id',$request->booking_no)->sum('total');
            $rem=($container['total']-$prevTotal);
            $note []= 'Container No: '  .str_pad($container['container_no'], 12)
                    .' currentTotal: '  .$container['total']
                    .' prevTotal: '     .$prevTotal
                    .' remain: '        .$rem ;
            $grandTotal+=$rem;
        }        
        return view('invoice.invoice.create_debit',[
                'notes' => $note ?? null,
                'qty'=>count($containers),
                'detentionAmount'=>$grandTotal,
                'bldraft'=>$bldraft,
                'voyages'=>$voyages,
                'charges' => $charges,
                'selectedCode' => $selectedCode,
                'bookingDetails'=>json_encode($containers),
                ]);
        
    }

}