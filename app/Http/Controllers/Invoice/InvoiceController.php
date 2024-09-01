<?php

namespace App\Http\Controllers\Invoice;

use App\Filters\Invoice\InvoiceIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Booking\Booking;
use App\Models\Containers\Demurrage;
use App\Models\Invoice\ChargesDesc;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceChargeDesc;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Quotations\LocalPortTriff;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::filter(new InvoiceIndexFilter(request()))
        ->where('company_id',Auth::user()->company_id)->with('chargeDesc','bldraft','receipts')
        ->orderBy('date','desc')
        ->paginate(30); 

        $exportinvoices = Invoice::filter(new InvoiceIndexFilter(request()))->orderBy('id','desc')
        ->where('company_id',Auth::user()->company_id)->with('chargeDesc','bldraft','receipts')->get();
        //dd($exportinvoices);
        session()->flash('invoice',$exportinvoices);
        $invoiceRef = Invoice::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();
        $bldrafts = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $voyages    = Voyages::where('company_id',Auth::user()->company_id)->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        $etd = VoyagePorts::get();
        $invoice_item = ChargesDesc::orderBy('id')->get();
        
        return view('invoice.invoice.index',[
            'invoices'=>$invoices,
            'invoiceRef'=>$invoiceRef,
            'bldrafts'=>$bldrafts,
            'customers'=>$customers,
            'voyages'=>$voyages,
            'etd'=>$etd,
            'invoice_item'=>$invoice_item,
        ]);
    }

    //Start Invoice Store
    public function selectBLinvoice()
    {
        $bldrafts = BlDraft::select('*')
        ->where('company_id', Auth::user()->company_id)
        ->whereHas('booking', function($query) {
            $query->where('shipment_type', 'Export');
        })
        ->with(['booking' => function($query) {
            $query->where('shipment_type', 'Export');
        }])
        ->get();

        $booking = Booking::select('*')
            ->where('shipment_type','Import')
            ->where('company_id', Auth::user()->company_id)
            ->get();
    
        return view('invoice.invoice.selectBLinvoice',[
            'bldrafts' => $bldrafts,
            'booking' => $booking,
        ]);
    }
    
    public function create_invoice(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $customers = Customers::where('company_id', $companyId)->get();
        $charges = ChargesDesc::orderBy('id')->get();
        $voyages = Voyages::with('vessel')->where('company_id', $companyId)->get();
    
        $blId = null;
        $bldraft = null;
        $qty = 0;
        $equipmentTypes = collect();
        $triffDetails = null;
    
        if ($request->has('bldraft_id')) {
            $blId = $request->input('bldraft_id');
            $bldraft = BlDraft::where('id', $blId)->with('blDetails')->first();
            $qty = $bldraft->blDetails->count();
            $equipmentTypeIds = $bldraft->booking->bookingContainerDetails->pluck('container_type')->toArray();
            $equipmentTypes = $this->getEquipmentTypes($equipmentTypeIds);
            $triffDetails = $this->getTriffDetails($companyId, $bldraft, $equipmentTypes);
        } elseif ($request->has('booking_ref')) {
            $blId = $request->input('booking_ref');
            $bldraft = Booking::where('id', $blId)->with('bookingContainerDetails')->first();
            $qty = $bldraft->bookingContainerDetails->count();
            $equipmentTypeIds = $bldraft->bookingContainerDetails->pluck('container_type')->toArray();
            $equipmentTypes = $this->getEquipmentTypes($equipmentTypeIds);
            $triffDetails = $this->getTriffDetails($companyId, $bldraft, $equipmentTypes);
        }
    
        return view('invoice.invoice.create_invoice', [
            'qty' => $qty,
            'bldraft' => $bldraft,
            'triffDetails' => $triffDetails,
            'voyages' => $voyages,
            'charges' => $charges,
            'customers' => $customers,
        ]);
    }
    
    private function getEquipmentTypes($equipmentTypeIds)
    {
        if (empty($equipmentTypeIds)) {
            return collect();
        }
    
        return ContainersTypes::whereIn('id', $equipmentTypeIds)->orderBy('id')->get();
    }
    
    private function getTriffDetails($companyId, $bldraft, $equipmentTypes)
    {
        $isExport = optional($bldraft->booking)->shipment_type == "Export";
        $portId = $isExport ? $bldraft->load_port_id : $bldraft->discharge_port_id;
        $isImportOrExport = $isExport ? 1 : 0;
    
        return LocalPortTriff::where('company_id', $companyId)
            ->where('port_id', $portId)
            ->where('validity_to', '>=', Carbon::now()->format("Y-m-d"))
            ->with(['triffPriceDetailes' => function($query) use ($bldraft, $equipmentTypes, $isImportOrExport) {
                $query->where('is_import_or_export', $isImportOrExport)
                    ->where('standard_or_customise', 1)
                    ->where('currency', request('add_egp'))
                    ->where(function($query) use ($equipmentTypes) {
                        $query->whereIn('equipment_type_id', $equipmentTypes->pluck('id')->toArray())
                            ->orWhere('equipment_type_id', '100');
                    });
            }, 'triffPriceDetailes.charge'])
            ->first();
    }
    
    public function storeInvoice(Request $request)
    {
        //dd($request->input());
            request()->validate([
                'customer' => ['required'],
                'customer_id' => ['required'],
            ]);
    if(request()->input('add_egp') == 'USD'){
        $totalAmount = 0;
        foreach($request->input('invoiceChargeDesc',[])  as $desc){
            $totalAmount += $desc['total'];
        }
        if($totalAmount == 0){
            return redirect()->back()->with('error','Invoice Total Amount Can not be Equal Zero')->withInput($request->input());
        }
    }
            $invoice = Invoice::create([
                'booking_ref'=>$request->booking_ref ?? null,
                'bldraft_id'=>$request->bldraft_id ?? null,
                'qty'=>$request->qty,
                'tax_discount'=>$request->tax_discount,
                'customer'=>$request->customer,
                'customer_id'=>$request->customer_id,
                'company_id'=>Auth::user()->company_id,
                'user_id'=>Auth::user()->id,
                'invoice_no'=>'',
                'date'=>$request->date,
                'rate'=>$request->exchange_rate,
                'customize_exchange_rate'=>$request->customize_exchange_rate,
                'vat'=>$request->vat,
                'add_egp'=>$request->add_egp,
                'type'=>'invoice',
                'invoice_status'=>$request->invoice_status,
                'notes'=>$request->notes,
            ]);
        

            foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
                InvoiceChargeDesc::create([
                    'invoice_id'=>$invoice->id,
                    'charge_description'=>$chargeDesc['charge_description'],
                    'size_small'=>$chargeDesc['size_small'],
                    'total_amount'=>$chargeDesc['total'] ?? null,
                    'total_egy'=>$chargeDesc['egy_amount'],
                    'enabled'=>$chargeDesc['enabled'],
                    'add_vat'=>$chargeDesc['add_vat'],
                    'usd_vat'=>$chargeDesc['usd_vat'] ?? null,
                    'egp_vat'=>$chargeDesc['egp_vat'],
                ]);
            }

        $setting = Setting::find(1);
        $invoice_no = 'DRAFTV';
        $invoice_no = $invoice_no . str_pad( $setting->invoice_draft, 4, "0", STR_PAD_LEFT );
        $invoice->invoice_no = $invoice_no;
        $setting->invoice_draft += 1;
        $setting->save();
        $invoice->save();

        if ($request->has('bldraft_id')) {
            if($request->bldraft_id != '0'){
                $bldrafts = BlDraft::where('id',$request->input('bldraft_id'))->first();
                $bldrafts->has_bl = 1;
                $bldrafts->save();
            }
        }

        return redirect()->route('invoice.index')->with('success',trans('Invoice.created'));
    }
    //End Invoice Store

    //Start Update
    public function edit(Request $request, Invoice $invoice)
    {
        $charges = ChargesDesc::orderBy('id')->get();    
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $invoice_details = InvoiceChargeDesc::where('invoice_id',$invoice->id)->with('invoice')->get();

        if ($invoice->bldraft_id != 0) {
            $blId = $invoice->bldraft_id;
            $bldraft = BlDraft::where('id', $blId)->with('blDetails')->first();
            $qty = $bldraft->blDetails->count();
        } elseif ($invoice->booking_ref != 0) {
            $blId = $invoice->booking_ref;
            $bldraft = Booking::where('id', $blId)->with('bookingContainerDetails')->first();
            $qty = $bldraft->bookingContainerDetails->count();
        }
        
        $total = 0;
        $total_eg = 0;
        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
            $total_eg += $chargeDesc->total_egy;
        }

        return view('invoice.invoice.edit',[
            'invoice'=>$invoice,
            'bldraft'=>$bldraft,
            'qty'=>$qty,
            'voyages'=>$voyages,
            'invoice_details'=>$invoice_details,
            'total'=>$total,
            'charges'=>$charges,
            'total_eg'=>$total_eg,
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $setting = Setting::find(1);
        $this->authorize(__FUNCTION__, Invoice::class);
    
        $inputs = $request->except(['invoiceChargeDesc', '_token', 'removed']);
    
        if ($invoice->bldraft_id == 'Customize') {
            $totalAmount = collect($request->input('invoiceChargeDesc', []))
                ->sum('total_amount');
    
            if ($totalAmount == 0) {
                return redirect()->back()->with('error', 'Invoice Total Amount cannot be zero')->withInput($request->input());
            }
        }
  
        if ($invoice->invoice_status == 'ready_confirm' && $request->invoice_status == 'confirm') {
            $setting = Setting::find(1);
    
            switch ($invoice->type) {
                case 'invoice':
                    if ($invoice->add_egp == 'onlyegp') {
                        $inputs['invoice_no'] = 'E / ' . $setting->invoice_confirm_egp . ' / 24';
                        $setting->invoice_confirm_egp += 1;
                    } else {
                        $inputs['invoice_no'] = 'U / ' . $setting->invoice_confirm_usd . ' / 24';
                        $setting->invoice_confirm_usd += 1;
                    }
                    break;
    
                case 'debit':
                    $inputs['invoice_no'] = 'D / ' . $setting->debit_confirm . ' / 24';
                    $setting->debit_confirm += 1;
                    break;
            }
    
            $setting->save();
        }
    
        $invoice->update($inputs);
        InvoiceChargeDesc::destroy(explode(',', $request->removed));
        $invoice->createOrUpdateInvoiceChargeDesc($request->invoiceChargeDesc);
    
        return redirect()->route('invoice.index')->with('success', trans('Invoice.Updated.Success'));
    }

    //End Update 

    public function selectBL()
    {
        $bldrafts = BlDraft::select('*')
        ->where('company_id', Auth::user()->company_id)
        ->whereHas('booking', function($query) {
            $query->where('shipment_type', 'Export');
        })
        ->with(['booking' => function($query) {
            $query->where('shipment_type', 'Export');
        }])
        ->get();

        $booking = Booking::select('*')
            ->where('shipment_type','Import')
            ->where('company_id', Auth::user()->company_id)
            ->get();
    
        return view('invoice.invoice.selectBL',[
            'bldrafts' => $bldrafts,
            'booking' => $booking,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $ofrs = null;
        $containerDetails = [];
        $charges = ChargesDesc::where('type','0')->orderBy('id')->get();
        if(request('bldraft_id') == "customize"){
            $cons = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 2);
            })->with('CustomerRoles.role')->get();
            $shippers = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 1);
            })->with('CustomerRoles.role')->get();
            $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
            $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $bookings  = Booking::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();

            return view('invoice.invoice.create_customize_debit',[
                'shippers'=>$shippers,
                'cons'=>$cons,
                'voyages'=>$voyages,
                'ports'=>$ports,
                'bookings'=>$bookings,
                'charges'=>$charges,
            ]);

        }elseif ($request->has('bldraft_id')) {
            $blId = $request->input('bldraft_id');
            $bldraft = BlDraft::where('id', $blId)->with(['blDetails', 'booking.quotation.quotationDesc'])->first();
            $totalqty = $bldraft->booking->bookingContainerDetails->count();

            // Assuming similar logic is needed for bldraft_id as for booking_ref
            $containerDetails = $bldraft->booking->bookingContainerDetails
                ->groupBy('container_type')
                ->map(function ($group) use ($bldraft) {
                    // Retrieve the matching QuotationDes entry
                    $quotationDesc = $bldraft->booking->quotation->quotationDesc
                        ->where('equipment_type_id', $group->first()->container_type)
                        ->first();
                    return [
                        'type' => $group->first()->containerType->name,
                        'qty' => $group->sum('qty'),
                        'amount' => $quotationDesc ? $quotationDesc->ofr : 0,
                    ];
                });
        } elseif ($request->has('booking_ref')) {
            $blId = $request->input('booking_ref');
            $bldraft = Booking::where('id', $blId)->with(['bookingContainerDetails', 'quotation.quotationDesc'])->first();
            $containerDetails = $bldraft->bookingContainerDetails
            ->groupBy('container_type')
            ->map(function ($group) use ($bldraft) {
                // Check if the quotation and quotationDesc are available
                $quotationDesc =$bldraft->quotation 
                    ? $bldraft->quotation->quotationDesc
                        ->where('equipment_type_id', $group->first()->container_type)
                        ->first()
                    : null;
                    
                return [
                    'type'   => $group->first()->containerType->name,
                    'qty'    => $group->sum('qty'),
                    'amount' => $quotationDesc ? $quotationDesc->ofr : null, 
                ];
            });
        
        $totalqty = $containerDetails->sum('qty');
        } 

        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $cartData = json_decode(request('cart_data_for_invoice'));

        // Pass the data to your view
        return view('invoice.invoice.create_debit',[
            'cartData' => $cartData ?? null,
            'totalqty'=>$totalqty,
            'ofrs'=>$ofrs,
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,
            'charges' => $charges,
            'containerDetails' => $containerDetails, // Pass the grouped container details
        ]);
    }

    public function getBookingDetails($booking_ref)
    {
        $booking = Booking::with([
            'customer', 
            'forwarder', 
            'consignee', 
            'customerNotify', 
            'loadPort', 
            'dischargePort', 
            'voyage', 
            'equipmentsType'
        ])->where('id', $booking_ref)->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        // Calculate the total quantity based on the equipment type
        $totalQty = $booking->bookingContainerDetails->sum('qty');

        return response()->json([
            'customer_id' => $booking->customer_id,
            'customer_name' => $booking->customer ? $booking->customer->name : null,
            'load_port_id' => $booking->load_port_id,
            'load_port_name' => $booking->loadPort ? $booking->loadPort->name : null,
            'discharge_port_id' => $booking->discharge_port_id,
            'discharge_port_name' => $booking->dischargePort ? $booking->dischargePort->name : null,
            'voyage_id' => $booking->voyage_id,
            'voyage_name' => $booking->voyage ? $booking->voyage->voyage_no : null,
            'total_qty' => $totalQty,
        ]);
    }


    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Invoice::class);
            request()->validate([
                'customer' => ['required'],
                'customer_id' => ['required'],
            ]);

            $totalAmount = 0;
            foreach($request->input('invoiceChargeDesc',[])  as $desc){
                $totalAmount += $desc['total_amount'];
            }
            if($totalAmount == 0){
                return redirect()->back()->with('error','Invoice Total Amount Can not be Equal Zero')->withInput($request->input());
            }
             
            $invoice = Invoice::create([
                'booking_ref'=>$request->booking_ref,
                'customer'=>$request->customer,
                'customer_id'=>$request->customer_id,
                'company_id'=>Auth::user()->company_id,
                'user_id'=>Auth::user()->id,
                'invoice_no'=>'',
                'date'=>$request->date,
                'type'=>'debit',
                'invoice_status'=>$request->invoice_status,
                'add_egp'=>'false',
                'voyage_id'=>$request->voyage_id,
                'notes'=>$request->notes,
                'customize_exchange_rate'=>$request->customize_exchange_rate, 
            ]);
        
            $setting = Setting::find(1);
    
            $invoice_no = 'DRAFTD';
            $invoice_no = $invoice_no . str_pad( $setting->debit_draft, 4, "0", STR_PAD_LEFT );
            $setting->debit_draft += 1;
            $invoice->invoice_no = $invoice_no;
            $invoice->save();
            $setting->save();
          
            foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
                InvoiceChargeDesc::create([
                    'invoice_id'=>$invoice->id,
                    'charge_description'=>$chargeDesc['charge_description'],
                    'size_small'=>$chargeDesc['size_small'],
                    'total_amount'=>$chargeDesc['total_amount'],
                    'qty'=>$chargeDesc['qty'],
                    'container_type'=>$chargeDesc['container_type'],
                ]);
            }

        return redirect()->route('invoice.index')->with('success',trans('Invoice.created'));
    }

    public function show($id)
    {
        $invoice = Invoice::with('chargeDesc')->find($id);
        $firstVoyagePort =null;
        $qty =null;

        if($invoice->booking_ref != 0){
            $qty = $invoice->booking->bookingContainerDetails->count();
            $firstVoyagePort = VoyagePorts::where('voyage_id',optional($invoice->booking)->voyage_id)
            ->where('port_from_name',optional($invoice->booking->dischargePort)->id)->first();
        }elseif($invoice->bldraft_id != 0){
            $qty = $invoice->bldraft->booking->bookingContainerDetails->count();
            $firstVoyagePort = VoyagePorts::where('voyage_id',optional($invoice->booking)->voyage_id)
            ->where('port_from_name',optional($invoice->bldraft->booking->loadPort)->id)->first();
        }

        $vat = $invoice->vat;
        $vat = $vat / 100;
        $total = 0;
        $total_eg = 0;
        $total_after_vat = 0;
        $total_before_vat = 0;
        $total_eg_after_vat = 0;
        $total_eg_before_vat = 0;
        $totalAftereTax = 0;
        $totalAftereTax_eg = 0;

        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
            $total_eg += $chargeDesc->total_egy;
            //Tax
            $totalAftereTax = (($total * $invoice->tax_discount)/100);
            $totalAftereTax_eg = (($total_eg * $invoice->tax_discount)/100);
            //End Tax
           if($chargeDesc->add_vat == 1){
                $total_after_vat += ($vat * $chargeDesc->total_amount);
                $total_eg_after_vat += ($vat * $chargeDesc->total_egy);
            }
        }
            $total_before_vat = $total;
            if($total_after_vat != 0){
                $total = $total + $total_after_vat;
        }

        $total = round($total , 2);

        $exp = explode('.', $total);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $USD =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $USD =  ucfirst($f->format($exp[0]));
        }

        $total_eg_before_vat = $total_eg;
        if($total_eg_after_vat != 0){
            $total_eg = $total_eg + $total_eg_after_vat;
        }

        $total_eg = round($total_eg , 2);

        $exp = explode('.', $total_eg);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $EGP =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $EGP =  ucfirst($f->format($exp[0]));
        }

        if($invoice->type == 'debit'){
            return view('invoice.invoice.show_debit',[
                'invoice'=>$invoice,
                'qty'=>$qty,
                'total'=>$total,
                'total_eg'=>$total_eg,
                'firstVoyagePort'=>$firstVoyagePort,
                'USD'=>$USD,
                'EGP'=>$EGP,

            ]);
        }else{
            $gross_weight = 0;
            $amount = 0;
            if ($invoice->bldraft_id != 0) {
                $equipmentTypeIds = $invoice->bldraft->booking->bookingContainerDetails->pluck('container_type')->toArray();
            }else{
                $equipmentTypeIds = $invoice->booking->bookingContainerDetails->pluck('container_type')->toArray();
            }
            $equipmentTypes = collect(); // Initialize an empty collection
            
            // Check if $equipmentTypeIds is not empty, then fetch equipment types
            if (!empty($equipmentTypeIds)) {
                $equipmentTypes = ContainersTypes::whereIn('id', $equipmentTypeIds)
                    ->orderBy('id')
                    ->get();
            }        
            $equipmentTypeIds = $equipmentTypes->pluck('id')->toArray(); 

            if($invoice->booking_ref != 0){
                foreach($invoice->booking->bookingContainerDetails as $bldetail){
                    $gross_weight += $bldetail->gross_weight;
                }
                $triffDetails = LocalPortTriff::where('port_id', optional($invoice->bldraft)->load_port_id)
                ->where('validity_to', '>=', Carbon::now()->format("Y-m-d"))
                ->with(["triffPriceDetailes" => function($q) use($equipmentTypes) {
                    $q->where(function($query) use($equipmentTypes) {
                        $query->whereIn("equipment_type_id", $equipmentTypes->pluck('id')->toArray())
                              ->orWhere('equipment_type_id', '100');
                    });
                }])
                ->first();              
            }else{
                foreach($invoice->bldraft->booking->bookingContainerDetails as $bldetail){
                    $gross_weight += $bldetail->gross_weight;
                }
                $triffDetails = LocalPortTriff::where('port_id',$invoice->load_port)
                    ->where('validity_to','>=',Carbon::now()->format("Y-m-d"))
                    ->with(["triffPriceDetailes" => function($q) use($invoice){
                        $q->where("equipment_type_id", optional($invoice->equipmentsType)->id);
                        $q->orwhere('equipment_type_id','100');
                    }])->first();
            }
      

            foreach($invoice->chargeDesc as $charge){
                $amount = $amount + ( (float)$charge->size_small);
            }

            return view('invoice.invoice.show_invoice',[
                'invoice'=>$invoice,
                'qty'=>$qty,
                'total'=>$total,
                'total_eg'=>$total_eg,
                'amount'=>$amount,
                'gross_weight'=>$gross_weight,
                'firstVoyagePort'=>$firstVoyagePort,
                'USD'=>$USD,
                'EGP'=>$EGP,
                'total_after_vat'=>$total_after_vat,
                'total_before_vat'=>$total_before_vat,
                'total_eg_after_vat'=>$total_eg_after_vat,
                'total_eg_before_vat'=>$total_eg_before_vat,
                'totalAftereTax'=>$totalAftereTax,
                'totalAftereTax_eg'=>$totalAftereTax_eg,
                'triffDetails'=>$triffDetails,
            ]);
        }
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        InvoiceChargeDesc::where('invoice_id',$id)->delete();
        $invoice->delete();
        return back()->with('success',trans('Invoice.Deleted.Success'));
    }

    public function invoiceJson($id){
        $invoice = Invoice::find($id);
        $invoiceModel = $invoice->getTaxInvoiceModel();
        return $invoiceModel->toJson();
    }
}