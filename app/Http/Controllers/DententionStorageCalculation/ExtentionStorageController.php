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
use App\Models\Invoice\InvoiceBooking;

class ExtentionStorageController extends Controller
{
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

        $codes=['SNTC','No Next Move'];
        $data = session('storage_ext');
        $containers=json_decode($data,true);
        $containers = array_filter($containers, function($container) use ($codes) {
            return in_array($container['to_code'],$codes);
        });
        if (empty($containers)) {
            return back()->with(['warning'=>trans('home.extention_msg'), 'input' => $request->input()]);
        }
        $charges = ChargesDesc::firstWhere('code','EG-560161093-MSL001');
        // $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();
        if ($request->has('bldraft_id')) {
            $bldraft = BlDraft::where('booking_id', $request->booking_no)->with('blDetails')->first();
            $request['bldraft_id']=$bldraft->id;
            $qty = $bldraft->blDetails->count();
        } elseif ($request->has('booking_ref')) {
            $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();
            $qty = $bldraft->bookingContainerDetails->count();
        }
        $voyages = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $invoiceBooking = new InvoiceBooking();
        
        $invoice = Invoice::with('invoiceBooking')->where('booking_ref',$request->booking_no)
                            ->where('type','invoice')
                            ->latest('date')->first();
        if($invoice){
            $invoiceBooking = $invoice->invoiceBooking()->whereIn('to_code',$codes)->get();
        }
        $grandTotal=0;
        $note=[];
        foreach ($containers as $container) {
            $rem=0;
            $prevTotal=$invoiceBooking->where('container_id',$container['container_id'])->where('booking_id',$request->booking_no)->sum('total');
            $rem=($container['total']-$prevTotal);
            $note []= 'Container No: ' .str_pad($container['container_no'], 12)
            .' currentTotal: ' .$container['total']
            .' prevTotal: ' .$prevTotal
            .' remain: ' .$rem ;
            $grandTotal+=$rem;
        }
        return view('invoice.invoice.create_invoice',[
            'notes' => $note ?? null,
            'qty'=>count($containers),
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,
            'charges' => [],
            'code' => $charges,
            'total_storage'=>$grandTotal,
            'bookingDetails'=>json_encode($containers),
        ]);
    }

}