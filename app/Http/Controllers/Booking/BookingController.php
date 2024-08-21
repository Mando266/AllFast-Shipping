<?php

namespace App\Http\Controllers\Booking;

use App\Filters\Quotation\QuotationIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingContainerDetails;
use App\Models\Booking\BookingRefNo;
use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Master\Suppliers;
use App\Models\Master\Terminals;
use App\Models\Master\Vessels;
use App\Models\Quotations\Quotation;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__, Booking::class);
        $booking = Booking::filter(new QuotationIndexFilter(request()))->orderBy('id', 'desc')
            ->where('company_id', Auth::user()->company_id)->where('shipment_type','Import')->with('bookingContainerDetails')->paginate(30);
        $exportbooking = request();
        $voyages = Voyages::with('vessel')->where('company_id', Auth::user()->company_id)->get();
        $bookingNo = Booking::where('company_id', Auth::user()->company_id)->where('shipment_type','Import')->get();
        $quotation = Quotation::where('company_id', Auth::user()->company_id)->where('shipment_type','Import')->get();
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();

        $customers = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 1);
            }
        )->with('CustomerRoles.role')->orderBy('id')->get();

        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();

        $consignee =  Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 2);
        })->with('CustomerRoles.role')->get();

        $line = Lines::where('company_id', Auth::user()->company_id)->get();
        $containers = Containers::where('company_id', Auth::user()->company_id)->get();

        return view('booking.booking.index', [
            'items' => $booking,
            'bookingNo' => $bookingNo,
            'exportbooking' => $exportbooking,
            'voyages' => $voyages,
            'quotation' => $quotation,
            'ports' => $ports,
            'customers' => $customers,
            'consignee' => $consignee,
            'ffw' => $ffw,
            'line' => $line,
            'containers' => $containers,
        ]);
    }

    public function export()
    {
        $booking = Booking::filter(new QuotationIndexFilter(request()))->orderBy('id', 'desc')
            ->where('company_id', Auth::user()->company_id)->where('shipment_type','Export')->with('bookingContainerDetails')->paginate(30);
        $exportbooking = request();
        $voyages = Voyages::with('vessel')->where('company_id', Auth::user()->company_id)->get();
        $bookingNo = Booking::where('company_id', Auth::user()->company_id)->where('shipment_type','Export')->get();
        $quotation = Quotation::where('company_id', Auth::user()->company_id)->where('shipment_type','Export')->get();
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();

        $customers = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 1);
            }
        )->with('CustomerRoles.role')->orderBy('id')->get();

        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();

        $consignee =  Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 2);
        })->with('CustomerRoles.role')->get();

        $line = Lines::where('company_id', Auth::user()->company_id)->get();
        $containers = Containers::where('company_id', Auth::user()->company_id)->get();

        return view('booking.booking.export', [
            'items' => $booking,
            'bookingNo' => $bookingNo,
            'exportbooking' => $exportbooking,
            'voyages' => $voyages,
            'quotation' => $quotation,
            'ports' => $ports,
            'customers' => $customers,
            'consignee' => $consignee,
            'ffw' => $ffw,
            'line' => $line,
            'containers' => $containers,
        ]);
    }

    public function selectImportQuotation()
    {
        $quotation = Quotation::where('company_id', Auth::user()->company_id)->where('shipment_type','Import')
        ->where('status', 'approved')->with(
            'customer',
            'equipmentsType'
        )->get();
        return view('booking.booking.selectImportQuotation', [
            'quotation' => $quotation,
        ]);
    }

    public function selectExportQuotation()
    {
        $quotation = Quotation::where('company_id', Auth::user()->company_id)->where('shipment_type','Export')
        ->where('status', 'approved')->with(
            'customer',
            'equipmentsType'
        )->get();
        return view('booking.booking.selectExportQuotation', [
            'quotation' => $quotation,
        ]);
    }

    public function selectBooking()
    {
        $bookings = Booking::where('company_id', Auth::user()->company_id)->get();
        return view('booking.booking.selectBooking', [
            'bookings' => $bookings,
        ]);
    }
    public function create()
    {
        $this->authorize(__FUNCTION__, Booking::class);
        request()->validate([
            'quotation_id' => ['required'],
        ]);
        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $notify = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 3);
            }
        )->with('CustomerRoles.role')->get();
        $consignee = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 2);
            }
        )->with('CustomerRoles.role')->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 1);
            }
        )->with('CustomerRoles.role')->get();
        $terminals = Terminals::get();
        if (request('quotation_id') == '0') {
            $quotation = new Quotation();
        } else {
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where(
                'port_id',
                $quotation->discharge_port_id
            )->get();
        }

        $agents = Agents::where('company_id', Auth::user()->company_id)->where('is_active', 1)->get();
        $terminal = Terminals::get();

        $vessels = Vessels::where('company_id', Auth::user()->company_id)->get();
        if (request('quotation_id') == '0') {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->get();
        } else {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->whereHas(
                'voyagePorts',
                function ($query) use ($quotation) {
                    $query->where('port_from_name', $quotation->discharge_port_id);
                }
            )->get();
        }

        $equipmentTypeIds = [];
        if (request('quotation_id') == '0') {
            $equipmentTypes = ContainersTypes::orderBy('id')->get();
            $containers = Containers::where('company_id', Auth::user()->company_id)->get();
        } else {
            $equipmentTypeIds = $quotation->quotationDesc->pluck('equipment_type_id')->toArray();
            $equipmentTypes = collect(); // Initialize an empty collection
            if (!empty($equipmentTypeIds)) {
                $equipmentTypes = ContainersTypes::whereIn('id', $equipmentTypeIds)
                    ->orderBy('id')
                    ->get();
            }
            $containers = Containers::where('company_id', Auth::user()->company_id)
                ->whereIn('container_type_id', $equipmentTypeIds)
                ->get();
        }

        $activityLocations = Ports::get();
        $ports = Ports::get();

        if ($quotation->shipment_type == 'Export') {
            $activityLocations = Ports::where('country_id', $quotation->countrydis)->get();
        } elseif ($quotation->shipment_type == 'Import') {
            $activityLocations = Ports::where('country_id', $quotation->countryload)->get();
        }elseif (request('quotation_id') == '0') {
            $activityLocations = Ports::get();
        }

        $line = Lines::where('company_id', Auth::user()->company_id)->get();

        return view('booking.booking.create', [
            'ffw' => $ffw,
            'consignee' => $consignee,
            'notify' =>$notify,
            'containers' => $containers,
            'agents' => $agents,
            'terminals' => $terminals,
            'equipmentTypeIds' => $equipmentTypeIds,
            'equipmentTypes'=>$equipmentTypes,
            'quotation' => $quotation,
            'terminal' => $terminal,
            'customers' => $customers,
            'vessels' => $vessels,
            'voyages' => $voyages,
            'activityLocations' => $activityLocations,
            'line' => $line,
            'ports' => $ports,
        ]);
    }

    public function exportcreate()
    {
        request()->validate([
            'quotation_id' => ['required'],
        ]);
        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $notify = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 3);
            }
        )->with('CustomerRoles.role')->get();
        $consignee = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 2);
            }
        )->with('CustomerRoles.role')->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 1);
            }
        )->with('CustomerRoles.role')->get();
        $terminals = Terminals::get();
        if (request('quotation_id') == '0') {
            $quotation = new Quotation();
        } else {
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where(
                'port_id',
                $quotation->load_port_id
            )->get();
        }

        $agents = Agents::where('company_id', Auth::user()->company_id)->where('is_active', 1)->get();
        $terminal = Terminals::get();

        $vessels = Vessels::where('company_id', Auth::user()->company_id)->get();
        if (request('quotation_id') == '0') {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->get();
        } else {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->whereHas(
                'voyagePorts',
                function ($query) use ($quotation) {
                    $query->where('port_from_name', $quotation->load_port_id);
                }
            )->get();
        }

        $equipmentTypeIds = [];
        if (request('quotation_id') == '0') {
            $equipmentTypes = ContainersTypes::orderBy('id')->get();
            $containers = Containers::where('company_id', Auth::user()->company_id)->get();
        } else {
            $equipmentTypeIds = $quotation->quotationDesc->pluck('equipment_type_id')->toArray();
            $equipmentTypes = collect(); // Initialize an empty collection
            if (!empty($equipmentTypeIds)) {
                $equipmentTypes = ContainersTypes::whereIn('id', $equipmentTypeIds)
                    ->orderBy('id')
                    ->get();
            }
            $containers = Containers::where('company_id', Auth::user()->company_id)
                ->whereIn('container_type_id', $equipmentTypeIds)
                ->get();
        }
        $activityLocations = Ports::get();
        $ports = Ports::get();

        if ($quotation->shipment_type == 'Export') {
            $activityLocations = Ports::where('country_id', $quotation->countrydis)->get();
        }elseif (request('quotation_id') == '0') {
            $activityLocations = Ports::get();
        }

        $line = Lines::where('company_id', Auth::user()->company_id)->get();

        return view('booking.booking.exportcreate', [
            'ffw' => $ffw,
            'consignee' => $consignee,
            'notify' =>$notify,
            'containers' => $containers,
            'agents' => $agents,
            'terminals' => $terminals,
            'equipmentTypeIds' => $equipmentTypeIds,
            'equipmentTypes'=>$equipmentTypes,
            'quotation' => $quotation,
            'terminal' => $terminal,
            'customers' => $customers,
            'vessels' => $vessels,
            'voyages' => $voyages,
            'activityLocations' => $activityLocations,
            'line' => $line,
            'ports' => $ports,
        ]);
    }

    public function checkContainer(Request $request)
    {
        try {
            $container = Containers::where('code', $request->input('number'))->first();
            if ($container) {
                return response()->json([
                    'exists' => true,
                    'type' => $container->container_type_id,
                    'ownership' => $container->container_ownership_id,
                    'haz' => $container->haz,
                    'weight' => $container->weight,
                    'vgm' => $container->vgm,
                ]);
            } else {
                return response()->json(['exists' => false]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error checking container: ' . $e->getMessage()], 500);
        }
    }

    public function createContainer(Request $request)
    {
        try {
            $containers = $request->input('containers');
            $createdContainers = [];

            foreach ($containers as $containerData) {
                $container = Containers::firstOrCreate(
                    ['code' => $containerData['container_number']],
                    [
                        'container_type_id' => $containerData['container_type'],
                        'activity_location_id' => $containerData['activity_location_id'],
                        'company_id' => Auth::user()->company_id,
                    ]
                );
                $createdContainers[] = [
                    'id' => $container->id,
                    'container_number' => $container->code
                ];
            }

            return response()->json(['success' => true, 'containers' => $createdContainers]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating container: ' . $e->getMessage()], 500);
        }
    }



    public function store(Request $request)
    {
        $request->validate([
            'discharge_port_id' => ['required', 'different:load_port_id'],
        ], [
            'discharge_port_id.different' => 'Load Port The Same Discharge Port',
        ]);
    
        $uniqueContainers = [];
        foreach ($request->input('containerDetails', []) as $container) {
            $key = $request->input('shipment_type') == 'Import' ? 'container_number' : 'container_id';
            $value = $container[$key] ?? null;
    
            if ($value && in_array($value, $uniqueContainers)) {
                return redirect()->back()->with('error', 'Container Numbers must be unique')->withInput($request->input());
            }
    
            if ($value) {
                $uniqueContainers[] = $value;
            }
        }
    
        $user = Auth::user();

        $ReferanceNumber = Booking::where('company_id', $user->company_id)->where('ref_no', $request->ref_no)->first();
        if ($ReferanceNumber != null) {
            return back()->with('error', 'This Booking No Already Exists');
        }

        // Validate Expiration Date
        $quotation = Quotation::find($request->quotation_id);
        $etaDate = VoyagePorts::where('voyage_id', $request->voyage_id)
            ->where('port_from_name', $request->load_port_id)
            ->pluck('eta')
            ->first();

        // if ($quotation != null && $quotation->shipment_type == 'Export') {
        //     if($etaDate <= $quotation->validity_from || $etaDate >= $quotation->validity_to) {
        //         return redirect()->back()->with('error','Invalid Date '.$etaDate.' Date Must Be Between '.$quotation->validity_from.' and '.$quotation->validity_to)
        //             ->withInput($request->input());
        //     }
        // }
        // Check for container details and create new containers if they don't exist
        $containerDetailsWithIds = [];

    if($request->input('shipment_type') == 'Import') {
        foreach ($request->input('containerDetails', []) as $details) {
            $containerNumber = $details['container_number'];
            $containerType = $details['container_type'];
            $activityLocationId = $details['activity_location_id'] ?? 1; // Default or specified

            if ($containerNumber && $containerType) {
                $container = Containers::firstOrCreate(
                    ['code' => $containerNumber],
                    [
                        'container_type_id' => $containerType,
                        'activity_location_id' => $activityLocationId,
                        'company_id' => $user->company_id,
                    ]
                );


                $details['container_id'] = $container->id;
                $containerDetailsWithIds[] = $details;
            }
        }
    } else { // For export bookings
        $containerDetailsWithIds = $request->input('containerDetails', []);
    }

        $booking = Booking::create([
            'ref_no' => "",
            'booked_by' => $user->id,
            'company_id' => $user->company_id,
            'quotation_id' => $request->input('quotation_id'),
            'customer_id' => $request->input('customer_id'),
            'customer_consignee_id' => $request->input('customer_consignee_id'),
            'reciver_customer' => $request->input('reciver_customer'),
            'bl_release' => $request->input('bl_release'),
            // 'place_of_acceptence_id' => $request->input('place_of_acceptence_id'),
            'load_port_id' => $request->input('load_port_id'),
            'pick_up_location' => $request->input('pick_up_location'),
            // 'place_return_id' => $request->input('place_return_id'),
            'shipper_ref_no' => $request->input('shipper_ref_no'),
            'place_of_delivery_id' => $request->input('place_of_delivery_id'),
            'discharge_port_id' => $request->input('discharge_port_id'),
            'forwarder_ref_no' => $request->input('forwarder_ref_no'),
            'voyage_id' => $request->input('voyage_id'),
            'voyage_id_second' => $request->input('voyage_id_second'),
            'terminal_id' => $request->input('terminal_id'),
            'tariff_service' => $request->input('tariff_service'),
            'commodity_code' => $request->input('commodity_code'),
            'commodity_description' => $request->input('commodity_description'),
            'soc' => $request->input('soc') != null ? 1 : 0,
            'imo' => $request->input('imo') != null ? 1 : 0,
            'rf' => $request->input('rf') != null ? 1 : 0,
            'oog' => $request->input('oog') != null ? 1 : 0,
            'coc' => $request->input('coc') != null ? 1 : 0,
            'ffw_id' => $request->input('ffw_id'),
            'booking_confirm' => $request->input('booking_confirm'),
            'notes' => $request->input('notes'),
            'principal_name' => $request->input('principal_name'),
            'vessel_name' => $request->input('vessel_name'),
            'transhipment_port' => $request->input('transhipment_port'),
            'acid' => $request->input('acid'),
            'shipment_type' => $request->input('shipment_type'),
            'movement'=> $request->input('movement'),
            'exportal_id'=> $request->input('exportal_id'),
            'importer_id'=> $request->input('importer_id'),
            'booking_type'=> $request->input('booking_type'),
            'free_time'=> $request->input('free_time'),
            'payment_kind'=> $request->input('payment_kind'),

        ]);
        $has_gate_in = 0;
        foreach ($containerDetailsWithIds as $details) {
            if ($details['container_id'] != null) {
                $has_gate_in = 1;
            }
            BookingContainerDetails::create([
                'seal_no' => $details['seal_no'],
                'qty' => $details['qty'] ?? 1,
                'container_id' => $details['container_id'],
                'booking_id' => $booking->id,
                'container_type' => $details['container_type'],
                'haz' => $details['haz'],
                'activity_location_id' => $details['activity_location_id'],
                'weight' => $details['weight'], //gross 
                'net_weight'=>$details['net_weight'],
                'packs'=>$details['packs'],
                'pack_type'=>$details['pack_type'], 
                'descripion'=>$details['descripion'] ?? null,  
              ]);
        }

        $booking->has_gate_in = $has_gate_in;
        $booking = $booking->load('loadPort');
        $booking = $booking->load('dischargePort');
        $bookingCounter = BookingRefNo::where('company_id', $user->company_id)->where(
            'port_of_load_id',
            $booking->load_port_id
        )->first();
        if (!isset($bookingCounter)) {
            $bookingCounter = BookingRefNo::create([
                'company_id' => $user->company_id,
                'port_of_load_id' => $booking->load_port_id,
                'counter' => 0
            ]);
        }
        // check if quotation Export create serial No else will not create serial No
        $quotation = Quotation::find($request->input('quotation_id'));

        if (Auth::user()->company_id == 1 && optional($booking)->shipment_type == "Import"){
            $booking->ref_no = $request->input('ref_no');
            $setting = Setting::find(1);
            $booking->delivery_no = substr($booking->dischargePort->code , -3).'IMP / ' .$setting->delivery_no . '/ 24';
            $setting->delivery_no += 1;
            $setting->save();
        }elseif (Auth::user()->company_id == 1 && optional($quotation)->shipment_type == "Export") {
            $setting = Setting::find(1);
            $booking->ref_no = 'DAH' . substr($booking->loadPort->code, -3) . substr($booking->dischargePort->code, -3)
            . sprintf(
                '%06u',
        $setting->booking_ref_no
            );
            $setting->booking_ref_no += 1;
            $setting->save();
        }else{
            $booking->ref_no = $request->input('ref_no');
        }
        $booking->save();

        if ($request->hasFile('certificat')) {
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $booking->update(['certificat' => "certificat/" . $path]);
        }
        if (optional($booking)->shipment_type == "Import"){
            return redirect()->route('booking.index')->with('success', trans('Booking.created'));
        }
        else{
            return redirect()->route('booking.export')->with('success', trans('Booking.created'));

        }
    }

    public function showShippingOrder($id)
    {
        $booking = Booking::with(
            'bookingContainerDetails.containerType',
            'bookingContainerDetails.container',
            'voyage.vessel',
            'secondvoyage.vessel'
        )->find($id);
        $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();
        $secondVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id_second)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();

        return view('booking.booking.showShippingOrder', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
            'secondVoyagePort' => $secondVoyagePort,
        ]);
    }

    public function deliveryOrder($id)
    {
        $booking = Booking::with(
            'bookingContainerDetails.containerType',
            'bookingContainerDetails.container',
            'voyage.vessel',
            'secondvoyage.vessel'
        )->find($id);

        $voyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->dischargePort)->id
        )->first();

        return view('booking.booking.deliveryOrder', [
            'booking' => $booking,
            'voyagePort' => $voyagePort,
        ]);
    }

    public function arrivalNotification($id)
    {
        $booking = Booking::with(
            'bookingContainerDetails.containerType',
            'bookingContainerDetails.container',
            'voyage.vessel',
            'secondvoyage.vessel'
        )->find($id);
        $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->dischargePort)->id
        )->first();
    


        return view('booking.booking.arrivalNotification', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
        ]);
    }

    public function showGateIn($id)
    {
        $booking = Booking::with(
            'bookingContainerDetails.containerType',
            'bookingContainerDetails.container',
            'voyage.vessel',
            'secondvoyage.vessel'
        )->find($id);
        
        $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();
        $secondVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id_second)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();

        return view('booking.booking.showGateIn', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
            'secondVoyagePort' => $secondVoyagePort
        ]);
    }

    public function selectGateInImport($id)
    {
        $booking = Booking::with(
            'bookingContainerDetails.containerType',
            'bookingContainerDetails.container',
            'voyage.vessel',
            'secondvoyage.vessel'
        )->find($id);
        $gateIns = collect();
        foreach ($booking->bookingContainerDetails as $detail) {
            if ($gateIns->count() == 0) {
                $port = Ports::find($detail->activity_location_id);
                $temp = collect([
                    'id' => $port->id,
                    'pick_up_location' => $port->pick_up_location,
                ]);
                $gateIns->add($temp->toArray());
            } else {
                $activityLocationAdded = false;
                foreach ($gateIns as $gateout) {
                    if ($gateout['id'] == $detail->activity_location_id) {
                        $activityLocationAdded = true;
                    }
                }
                if ($activityLocationAdded == false) {
                    $port = Ports::find($detail->activity_location_id);
                    $temp = collect([
                        'id' => $port->id,
                        'pick_up_location' => $port->pick_up_location,
                    ]);
                    $gateIns->add($temp->toArray());
                }
            }
        }
        if ($gateIns->count() == 1) {
            return redirect()->route('booking.showGateInImport', [
                'booking' => $booking->id,
                'location' => $gateIns[0]['id']
            ]);
        }
        return view('booking.booking.selectGateInImport', [
            'booking' => $booking,
            'gateIns' => $gateIns,
        ]);
    }

    public function showGateInImport($id)
    {
        if (request('location') == null) {
            $activityLoc = request()->activity_location_id;
        } else {
            $activityLoc = request('location');
        }
        $booking = Booking::with([
            'bookingContainerDetails' => function ($query) use ($activityLoc) {
                $query->where('activity_location_id', $activityLoc)->with('containerType', 'container');
            }
        ])->with('voyage.vessel', 'secondvoyage.vessel')->find($id);
        $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();
        $secondVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id_second)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();

        $firstVoyagePortImport = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->dischargePort)->id
        )->first();

        return view('booking.booking.showGateInImport', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
            'secondVoyagePort' => $secondVoyagePort,
            'firstVoyagePortImport' => $firstVoyagePortImport,
        ]);
    }

    // booking.showGateOut',['booking'=>$item->id]
    public function selectGateOut($id)
    {
        $booking = Booking::with(
            'bookingContainerDetails.containerType',
            'bookingContainerDetails.container',
            'voyage.vessel',
            'secondvoyage.vessel'
        )->find($id);
        $gateouts = collect();
        foreach ($booking->bookingContainerDetails as $detail) {
            if ($gateouts->count() == 0) {
                $port = Ports::find($detail->activity_location_id);
                $temp = collect([
                    'id' => $port->id,
                    'pick_up_location' => $port->pick_up_location,
                ]);
                $gateouts->add($temp->toArray());
            } else {
                $activityLocationAdded = false;
                foreach ($gateouts as $gateout) {
                    if ($gateout['id'] == $detail->activity_location_id) {
                        $activityLocationAdded = true;
                    }
                }
                if ($activityLocationAdded == false) {
                    $port = Ports::find($detail->activity_location_id);
                    $temp = collect([
                        'id' => $port->id,
                        'pick_up_location' => $port->pick_up_location,
                    ]);
                    $gateouts->add($temp->toArray());
                }
            }
        }
        if ($gateouts->count() == 1) {
            return redirect()->route('booking.showGateOut', [
                'booking' => $booking->id,
                'location' => $gateouts[0]['id']
            ]);
        }
        return view('booking.booking.selectGateOut', [
            'booking' => $booking,
            'gateouts' => $gateouts,
        ]);
    }

    public function showGateOut($id)
    {
        if (request('location') == null) {
            $activityLoc = request()->activity_location_id;
        } else {
            $activityLoc = request('location');
        }
        $booking = Booking::with([
            'bookingContainerDetails' => function ($query) use ($activityLoc) {
                $query->where('activity_location_id', $activityLoc)->with('containerType', 'container');
            }
        ])->with('voyage.vessel', 'secondvoyage.vessel')->find($id);

        if( $booking->shipment_type == 'Import'){
            $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
                'port_from_name',
                optional($booking->dischargePort)->id
            )->first();
        }else{
            $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
                'port_from_name',
                optional($booking->loadPort)->id
            )->first();
        }

        $secondVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id_second)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();
        $firstVoyagePortImport = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->dischargePort)->id
        )->first();
        $secondVoyagePortImport = VoyagePorts::where('voyage_id', $booking->voyage_id_second)->where(
            'port_from_name',
            optional($booking->dischargePort)->id
        )->first();
        return view('booking.booking.showGateOut', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
            'secondVoyagePort' => $secondVoyagePort,
            'firstVoyagePortImport' => $firstVoyagePortImport,
            'secondVoyagePortImport' => $secondVoyagePortImport,
        ]);
    }

    public function show($id)
    {
        $booking = Booking::with(
            'bookingContainerDetails.containerType',
            'bookingContainerDetails.container',
            'voyage.vessel',
            'secondvoyage.vessel'
        )->find($id);
        $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();
        $secondVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id_second)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();


        return view('booking.booking.show', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
            'secondVoyagePort' => $secondVoyagePort
        ]);
    }
    public function getBookingDetails(Request $request, $bookingId)
    {
        try {
            $perPage = 20;
            $page = $request->input('page', 1);
            $offset = ($page - 1) * $perPage;

            $bookingDetails = BookingContainerDetails::where('booking_id', $bookingId)
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $totalRows = BookingContainerDetails::where('booking_id', $bookingId)->count();
            $totalPages = ceil($totalRows / $perPage);

            $equipmentTypes = ContainersTypes::orderBy('id')->get();
            $oldContainers = Containers::where('company_id', Auth::user()->company_id)->get();
            $activityLocations = Ports::get();

            return response()->json([
                'bookingDetails' => view('booking.partials.booking-details', compact('bookingDetails', 'equipmentTypes', 'oldContainers', 'activityLocations'))->render(),
                'totalPages' => $totalPages
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function edit(Booking $booking)
    {
        $this->authorize(__FUNCTION__, Booking::class);
        $quotationRate = Quotation::where('company_id', Auth::user()->company_id)->where('status', 'approved')->with(
            'customer',
            'equipmentsType'
        )->get();
        $booking_details = BookingContainerDetails::where('booking_id', $booking->id)->get();
        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $consignee = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 2);
            }
        )->with('CustomerRoles.role')->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 1);
            }
        )->with('CustomerRoles.role')->get();

        if (request('quotation_id') == '0' || $booking->quotation_id == null) {
            $quotation = new Quotation();
            $terminals = Terminals::where('company_id', Auth::user()->company_id)->get();
        } else {
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where('company_id', Auth::user()->company_id)->where(
                'port_id',
                $quotation->discharge_port_id
            )->get();
        }
        $agents = Agents::where('company_id', Auth::user()->company_id)->where('is_active', 1)->get();
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $terminal = Terminals::where('company_id', Auth::user()->company_id)->get();
        $vessels = Vessels::where('company_id', Auth::user()->company_id)->get();

         if (request('quotation_id') == 'draft' || $booking->quotation_id == null || $booking->transhipment_port != null ) {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->get();
        } elseif($quotation->shipment_type == 'Export') {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->whereHas(
                'voyagePorts',
                function ($query) use ($quotation) {
                    $query->where('port_from_name', $quotation->load_port_id);
                }
            )->get();
        }else{
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->whereHas(
                'voyagePorts',
                function ($query) use ($quotation) {
                    $query->where('port_from_name', $quotation->discharge_port_id);
                }
            )->get();
        }
        $ports = Ports::orderBy('id')->get();

        $containers = Containers::where('company_id', Auth::user()->company_id)->get();

        if($quotation->shipment_type == 'Export' && $booking->quotation_id != null){
            $containers = Containers::where('company_id', Auth::user()->company_id)->whereHas(
                'activityLocation',
                function ($query) use ($quotation) {
                    $query->where('country_id', $quotation->countrydis)->where(
                        'container_type_id',
                        $quotation->equipment_type_id
                    );
                }
            )->where('status', 2)->get();
        }elseif($booking->quotation_id != null){
            $containers = Containers::where('company_id', Auth::user()->company_id)->where(
                'container_type_id',
                $quotation->equipment_type_id
            )->get();
        }
        if($booking->quotation_id != null){
            $oldContainers = Containers::where('company_id', Auth::user()->company_id)->where(
            'container_type_id',
                $quotation->equipment_type_id
            )->get();
        }else{
            $oldContainers = Containers::where('company_id', Auth::user()->company_id)->get();
        }
        $activityLocations = Ports::get();

        if ($quotation->shipment_type == 'Export' && $booking->quotation_id != null) {
            $activityLocations = Ports::where('country_id', $quotation->countrydis)->where(
                'company_id',
                Auth::user()->company_id
            )->get();
        } elseif ($quotation->shipment_type != 'Export' && $booking->quotation_id != null) {
            $activityLocations = Ports::where('country_id', $quotation->countryload)->where(
                'company_id',
                Auth::user()->company_id
            )->get();
        }else{
            $activityLocations = Ports::get();

        }

        $line = Lines::where('company_id', Auth::user()->company_id)->get();
       

        return view('booking.booking.edit', [
            'quotationRate' => $quotationRate,
            'booking_details' => $booking_details,
            'booking' => $booking,
            'ffw' => $ffw,
            'consignee' => $consignee,
            'containers' => $containers,
            'oldContainers' => $oldContainers,
            'agents' => $agents,
            'terminals' => $terminals,
            'equipmentTypes' => $equipmentTypes,
            'ports' => $ports,
            'terminal' => $terminal,
            'customers' => $customers,
            'vessels' => $vessels,
            'voyages' => $voyages,
            'quotation' => $quotation,
            'activityLocations' => $activityLocations,
            'line' => $line,
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'voyage_id' => ['required'],
                'commodity_description' => ['required'],
                'bl_kind' => ['required'],  // Add bl_kind to the validation rules
            ]);
    
            $quotation = Quotation::find($request->quotation_id);
            $etaDate = VoyagePorts::where('voyage_id', $request->voyage_id)->where('port_from_name', $request->load_port_id)->pluck('eta')->first();
    
            // Check for unique container IDs if movement is FCL/FCL
            if ($request->input('movement') == 'FCL/FCL') {
                $uniqueContainers = array();
                foreach ($request->containerDetails as $container) {
                    if (!in_array($container['container_id'], $uniqueContainers) || $container['container_id'] == null || $container['container_id'] == "000") {
                        if ($container['container_id'] != "000" || $container['container_id'] != null) {
                            array_push($uniqueContainers, $container['container_id']);
                        }
                    } else {
                        return redirect()->back()->with('error', 'Container Numbers Must be unique')->withInput($request->input());
                    }
                }
            }
        $user = Auth::user();
        $ReferanceNumber = Booking::where('id', '!=', $booking->id)->where('company_id', $user->company_id)->where(
            'ref_no',
            $request->ref_no
        )->count();
            if ($ReferanceNumber > 0) {
                return back()->with('error', 'The Booking Reference Number Already Exists');
            }
    
            $this->authorize(__FUNCTION__, Booking::class);
            $inputs = request()->all();
            unset($inputs['containerDetails'], $inputs['_token'], $inputs['removed']);
    
            // Update booking information
            $booking->update($inputs);
    
            // Process container details
            foreach ($request->containerDetails as $container) {
                if (isset($container['id']) && !empty($container['id'])) {
                    // Update existing container
                    BookingContainerDetails::find($container['id'])->update($container);
                } else {
                    // Create new container
                    $container['booking_id'] = $booking->id;
                    BookingContainerDetails::create($container);
                }
            }
    
            // Remove deleted containers
            if (!empty($request->removed)) {
                BookingContainerDetails::destroy(explode(',', $request->removed));
            }
    
            // Process certificate file if uploaded
            if ($request->hasFile('certificat')) {
                $path = $request->file('certificat')->getClientOriginalName();
                $request->certificat->move(public_path('certificat'), $path);
                $booking->update(['certificat' => "certificat/" . $path]);
            }
    
            if ($booking->shipment_type == "Import") {
                return redirect()->route('booking.index')->with('success', trans('Booking.updated'));
            } else {
                return redirect()->route('booking.export')->with('success', trans('Booking.updated'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update booking. Please try again.');
        }
    }

    public function destroy($id)
    {
        $bookings = Booking::find($id);
        BookingContainerDetails::where('booking_id', $id)->delete();
        $bookings->delete();
        return back()->with('success', trans('Booking.Deleted.Success'));
    }

    public function temperatureDiscrepancy(Booking $booking)
    {
        $customers = Customers::allWithContactEmails(fn($q) => $q->orderBy('name'));
        $suppliers = Suppliers::allWithContactEmails(
            fn($q) => $q->where('is_container_depot', true)
                ->orWhere('is_container_services_provider', true)
                ->orderBy('name')
        );

        return view('booking.booking.temperatureDiscrepancy')
            ->with([
                'booking' => $booking,
                'bookingContainerDetails' => $booking->bookingContainerDetails,
                'customers' => $customers,
                'suppliers' => $suppliers
            ]);
    }


    public function clone($id)
    {
        // Retrieve the original booking to be cloned
        $originalBooking = Booking::with('bookingContainerDetails')->findOrFail($id);
    
        // Start a transaction to ensure atomicity
        DB::beginTransaction();
        try {
            // Clone the booking
            $newBooking = $originalBooking->replicate(); 
    
            // Check the shipment type and set the ref_no accordingly
            if ($originalBooking->shipment_type === 'Import') {
                $newBooking->ref_no = null;
            } elseif ($originalBooking->shipment_type === 'Export') {
                $newBooking->ref_no = $this->incrementRefNo($originalBooking->ref_no);
            }
            $newBooking->created_at = now(); 
            $newBooking->save(); 
    
            // Clone each container detail and assign it to the new booking
            foreach ($originalBooking->bookingContainerDetails as $detail) {
                $newDetail = $detail->replicate();
                $newDetail->booking_id = $newBooking->id; 
                $newDetail->save();
            }
    
            DB::commit();
    
            return redirect()->route('booking.index')->with('success', 'Booking cloned successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('booking.index')->with('error', 'Failed to clone booking: ' . $e->getMessage());
        }
    }
    
    protected function incrementRefNo($refNo)
    {
        // Example: if ref_no is "BOOK001", it will become "BOOK002"
        $number = preg_replace('/\D/', '', $refNo);
        $prefix = preg_replace('/\d/', '', $refNo);
        $newNumber = (int)$number + 1;
    
        return $prefix . str_pad($newNumber, strlen($number), '0', STR_PAD_LEFT);
    }

}
