<?php

namespace App\Http\Controllers\DententionStorageCalculation;

use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Voyages\Voyages;
use App\Models\Invoice\ChargesDesc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StorageInvoiceController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = session('storage_invoice');
        $charges = ChargesDesc::firstWhere('code','EG-560161093-MSL001');
        $bldraft = Booking::where('id', $request->booking_no)->with('bookingContainerDetails')->first();
        $qty = $bldraft->bookingContainerDetails->count();
        $voyages = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $containerDetails = [];
        foreach (json_decode($data,true) as $container) {
            $containerDetails []= 'Container No: ' .str_pad($container['container_no'], 12)
            .' To Code: ' .$container['to_code']
            .' daysCount: ' .$container['daysCount']
            .' freeTime: ' .$container['freeTime']
            .' Total: ' .$container['total']
            ;
        }
        return view('invoice.invoice.create_invoice',[
            'qty'=>$qty,
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,
            'charges' => [],
            'code' => $charges,
            'notes' => $containerDetails ?? null,
            'total_storage'=>$request->grandTotal,
        ]);
    }

}