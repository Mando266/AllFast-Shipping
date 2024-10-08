<?php

namespace App\Http\Controllers\DententionStorageCalculation;

use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Invoice\Invoice;
use App\Models\Voyages\Voyages;
use App\Models\Invoice\ChargesDesc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice\InvoiceBooking;

class ExtentionDententionController extends Controller
{
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handelExtentionDentention(Request $request)
    {
        $codes=['SNTC','No Next Move'];
        $containers=json_decode($request->data,true);
        $containers = array_filter($containers, function($container) use ($codes) {
            return in_array($container['to_code'],$codes);
        });
        if (empty($containers)) {
            return back()->with(['warning'=>trans('home.extention_msg'), 'input' => $request->input()]);
        }
        $selectedCode=40;
        $invoiceBooking = new InvoiceBooking();
        $invoice = Invoice::with('invoiceBooking')->where('booking_ref',$request->booking_no)
                    ->where('type','debit')
                    ->latest('date')->first();
        if($invoice){
            $invoiceBooking = $invoice->invoiceBooking()->whereIn('to_code',$codes)->get();
        }

        
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
        // return view('invoice.invoice.create_debit',[
        //         'notes' => $note ?? null,
        //         'totalqty'=>count($containers),
        //         'detentionAmount'=>$grandTotal,
        //         'bldraft'=>$bldraft,
        //         'voyages'=>$voyages,
        //         'charges' => $charges,
        //         'selectedCode' => $selectedCode,
        //         'bookingDetails'=>json_encode($containers),
        //         ]);
                
                           return http_build_query([
                           'notes' => $note ?? null,
                           'totalqty'=>count($containers),
                           'bookingDetails'=>json_encode($containers),
                           'detentionAmount'=>$grandTotal,
                           'booking_no' => $request->booking_no,
                           'selectedCode' => $selectedCode,
                           'booking_ref' => $request->booking_ref,
                           'grandTotal' => $request->grandTotal,
                           ]);
        
    }


    public function createExtentionDentention(Request $request)
    {
        $charges = ChargesDesc::orderBy('id')->get();
        $voyages = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();

        return view('invoice.invoice.create_debit',$request->all()+[
        'charges'=>$charges,
        'voyages'=>$voyages,
        'bldraft' => $bldraft,
        ]);
    }

}