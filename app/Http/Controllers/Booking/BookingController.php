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

class BookingController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__, Booking::class);
        $booking = Booking::filter(new QuotationIndexFilter(request()))->orderBy('id', 'desc')
            ->where('company_id', Auth::user()->company_id)->with('bookingContainerDetails')->paginate(30);
        $exportbooking = request();
        $voyages = Voyages::with('vessel')->where('company_id', Auth::user()->company_id)->get();
        $bookingNo = Booking::where('company_id', Auth::user()->company_id)->get();
        $quotation = Quotation::where('company_id', Auth::user()->company_id)->get();
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

    public function selectBooking()
    {
        $bookings = Booking::where('company_id', Auth::user()->company_id)->get();
        return view('booking.booking.selectBooking', [
            'bookings' => $bookings,
        ]);
    }

    public function selectQuotation()
    {
        $quotation = Quotation::where('company_id', Auth::user()->company_id)->where('status', 'approved')->with(
            'customer',
            'equipmentsType'
        )->get();
        return view('booking.booking.selectQuotation', [
            'quotation' => $quotation,
        ]);
    }

    public function referManifest()
    {
        $bookings = session('bookings');
        $booking = $bookings->first();
        $firstVoyagePort = VoyagePorts::where('voyage_id', $booking->voyage_id)->where(
            'port_from_name',
            optional($booking->loadPort)->id
        )->first();
        $qty = 0;
        foreach ($bookings as $item) {
            foreach ($item->bookingContainerDetails as $detail) {
                $qty += $detail->qty;
            }
        }
        return view('booking.booking.referManifest', compact('bookings', 'booking', 'firstVoyagePort', 'qty'));
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
        if (request('quotation_id') == 'draft') {
            $quotation = new Quotation();
        } else {
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where(
                'port_id',
                $quotation->discharge_port_id
            )->get();
//            dd($terminals,$quotation->discharge_port_id,Auth::user()->company_id);
    }

        $agents = Agents::where('company_id', Auth::user()->company_id)->where('is_active', 1)->get();
        
        $equipmentTypeIds = $quotation->quotationDesc->pluck('equipment_type_id')->toArray();
        $equipmentTypes = collect(); // Initialize an empty collection
        if (!empty($equipmentTypeIds)) {
            $equipmentTypes = ContainersTypes::whereIn('id', $equipmentTypeIds)
                ->orderBy('id')
                ->get();
        }
        
        //dd($equipmentTypes);
        $terminal = Terminals::get();
        $vessels = Vessels::where('company_id', Auth::user()->company_id)->get();
        if (request('quotation_id') == 'draft') {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->get();
        } else {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->whereHas(
                'voyagePorts',
                function ($query) use ($quotation) {
                    $query->where('port_from_name', $quotation->load_port_id);
                }
            )->get();
        }

        $ports = Ports::orderBy('id')->get();
        if($quotation->shipment_type == 'Export'){
            $containers = Containers::where('company_id', Auth::user()->company_id)->whereHas(
                'activityLocation',
                function ($query) use ($quotation) {
                    $query->where('country_id', $quotation->countrydis)->where(
                        'container_type_id',
                        $quotation->equipment_type_id
                    );
                }
            )->where('status', 2)->get();
        }else{
            $containers = Containers::where('company_id', Auth::user()->company_id)->whereHas(
                'activityLocation',
                function ($query) use ($quotation) {
                    $query->where('container_type_id',
                        $quotation->equipment_type_id
                    );
                }
            )->where('status', 2)->get();
        }
        $activityLocations = Ports::get();
        
        if ($quotation->shipment_type == 'Export') {
            $activityLocations = Ports::where('country_id', $quotation->countrydis)->get();
        } elseif ($quotation->shipment_type != 'Export') {
            $activityLocations = Ports::where('country_id', $quotation->countryload)->get();
        }

        $line = Lines::where('company_id', Auth::user()->company_id)->get();
        return view('booking.booking.create', [
            'ffw' => $ffw,
            'consignee' => $consignee,
            'containers' => $containers,
            'agents' => $agents,
            'terminals' => $terminals,
            'equipmentTypes' => $equipmentTypes,
            'ports' => $ports,
            'quotation' => $quotation,
            'terminal' => $terminal,
            'customers' => $customers,
            'vessels' => $vessels,
            'voyages' => $voyages,
            'activityLocations' => $activityLocations,
            'line' => $line,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'place_of_delivery_id' => ['required', 'different:place_of_acceptence_id'],
            'discharge_port_id' => ['required', 'different:load_port_id'],
        ], [
            'place_of_delivery_id.different' => 'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different' => 'Load Port The Same  Discharge Port',
        ]);
        // Validate Containers Unique
    if($request->input('movement') == 'FCL/FCL'){
        $uniqueContainers = array();
        foreach ($request->containerDetails as $container) {
            if (!in_array(
                    $container['container_id'],
                    $uniqueContainers
                ) || $container['container_id'] == null || $container['container_id'] == "000") {
                if ($container['container_id'] != "000" || $container['container_id'] != null) {
                    array_push($uniqueContainers, $container['container_id']);
                }
            } else {
                return redirect()->back()->with('error', 'Container Numbers Must be unique')->withInput(
                    $request->input()
                );
            }
        }
    }
        $user = Auth::user();

        $ReferanceNumber = Booking::where('company_id', $user->company_id)->where('ref_no', $request->ref_no)->first();
        //dd($BookingDublicate);
        if ($ReferanceNumber != null) {
            return back()->with('error', 'This Booking No Already Exists');
        }

        // Validate Expiration Date
        $quotation = Quotation::find($request->quotation_id);
        $etaDate = VoyagePorts::where('voyage_id',$request->voyage_id)->where('port_from_name',$request->load_port_id)->pluck('eta')->first();

    if($quotation != null){
        if($quotation->shipment_type == 'Export'){
                if($etaDate <= $quotation->validity_from && $etaDate >= $quotation->validity_to){
                    return redirect()->back()->with('error','Invalid Date '.$etaDate.' Date Must Be Between '.$quotation->validity_from.' and '.$quotation->validity_to)
                    ->withInput($request->input());
                }
        }
    }
        $user = Auth::user();

        $booking = Booking::create([
            'ref_no' => "",
            'booked_by' => $user->id,
            'company_id' => $user->company_id,
            'quotation_id' => $request->input('quotation_id'),
            'customer_id' => $request->input('customer_id'),
            'customer_consignee_id' => $request->input('customer_consignee_id'),
            'equipment_type_id' => $request->input('equipment_type_id'),
            'bl_release' => $request->input('bl_release'),
            'place_of_acceptence_id' => $request->input('place_of_acceptence_id'),
            'load_port_id' => $request->input('load_port_id'),
            'pick_up_location' => $request->input('pick_up_location'),
            'place_return_id' => $request->input('place_return_id'),
            'shipper_ref_no' => $request->input('shipper_ref_no'),
            'place_of_delivery_id' => $request->input('place_of_delivery_id'),
            'discharge_port_id' => $request->input('discharge_port_id'),
            'forwarder_ref_no' => $request->input('forwarder_ref_no'),
            'voyage_id' => $request->input('voyage_id'),
            'voyage_id_second' => $request->input('voyage_id_second'),
            'terminal_id' => $request->input('terminal_id'),
            'load_terminal_id' => $request->input('load_terminal_id'),
            'agent_id' => $request->input('agent_id'),
            'discharge_etd' => $request->input('discharge_etd'),
            'load_port_cutoff' => $request->input('load_port_cutoff'),
            'load_port_dayes' => $request->input('load_port_dayes'),
            'tariff_service' => $request->input('tariff_service'),
            'commodity_code' => $request->input('commodity_code'),
            'activity_location_id' => $request->input('activity_location_id'),
            'commodity_description' => $request->input('commodity_description'),
            'soc' => $request->input('soc') != null ? 1 : 0,
            'imo' => $request->input('imo') != null ? 1 : 0,
            'rf' => $request->input('rf') != null ? 1 : 0,
            'oog' => $request->input('oog') != null ? 1 : 0,
            'nor' => $request->input('nor') != null ? 1 : 0,
            'ffw_id' => $request->input('ffw_id'),
            'booking_confirm' => $request->input('booking_confirm'),
            'notes' => $request->input('notes'),
            'principal_name' => $request->input('principal_name'),
            'vessel_name' => $request->input('vessel_name'),
            'is_transhipment' => $request->input('is_transhipment'),
            'transhipment_port' => $request->input('transhipment_port'),
            'acid' => $request->input('acid'),
            'shipment_type' => $request->input('shipment_type'),
            'movement'=> $request->input('movement'),
            'exportal_id'=> $request->input('exportal_id'),
        ]);
        $has_gate_in = 0;
        foreach ($request->input('containerDetails', []) as $details) {
            if ($details['container_id'] != null && $details['container_id'] != 000) {
                $has_gate_in = 1;
            }
            BookingContainerDetails::create([
                'seal_no' => $details['seal_no'],
                'qty' => $details['qty'],
                'container_id' => $details['container_id'],
                'booking_id' => $booking->id,
                'container_type' => $booking->equipment_type_id,
                'haz' => $details['haz'],
                'activity_location_id' => $details['activity_location_id'],
                'weight' => $details['weight'],
                'vgm' => $details['vgm'],
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

        if (Auth::user()->company_id == 3 && optional($booking)->shipment_type == "Import"){
            $booking->ref_no = $request->input('ref_no');
            $setting = Setting::find(1);
            $booking->win_delivery_no = $setting->win_delivery_no += 1;
            $setting->save();
        }elseif (Auth::user()->company_id == 3 &&  optional($quotation)->shipment_type == "Export") {
            $setting = Setting::find(1);
            $booking->ref_no = 'WIN' . substr($booking->loadPort->code, -3) . substr($booking->dischargePort->code, -3) 
            . sprintf(
                    '%06u',
            $setting->win_booking_ref_no
                );
            $setting->win_booking_ref_no += 1;
            $setting->save();
    
        }elseif (optional($quotation)->shipment_type == "Export") {
            $setting = Setting::find(1);
            $booking->ref_no = 'CSDALY' //. $booking->loadPort->code . substr($booking->dischargePort->code, -3) 
            . sprintf(
                    '%06u',
            $setting->booking_ref_no
                );
            $setting->booking_ref_no += 1;
            $setting->save();
        } elseif (optional($booking)->shipment_type == "Import") {
            $booking->ref_no = $request->input('ref_no');
            $setting = Setting::find(1);
            $booking->deleviry_no = $setting->delivery_no += 1;
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

        return redirect()->route('booking.index')->with('success', trans('Booking.created'));
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
        $secondVoyagePortImport = VoyagePorts::where('voyage_id', $booking->voyage_id_second)->where(
            'port_from_name',
            optional($booking->dischargePort)->id
        )->first();
        return view('booking.booking.deliveryOrder', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
            'secondVoyagePort' => $secondVoyagePort,
            'firstVoyagePortImport' => $firstVoyagePortImport,
            'secondVoyagePortImport' => $secondVoyagePortImport,
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
        //dd($booking);
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

        //dd($booking);
        return view('booking.booking.show', [
            'booking' => $booking,
            'firstVoyagePort' => $firstVoyagePort,
            'secondVoyagePort' => $secondVoyagePort
        ]);
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

        if (request('quotation_id') == 'draft' || $booking->quotation_id == null) {
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
        } else {
            $voyages = Voyages::with('vessel', 'voyagePorts')->where('company_id', Auth::user()->company_id)->whereHas(
                'voyagePorts',
                function ($query) use ($quotation) {
                    $query->where('port_from_name', $quotation->load_port_id);
                }
            )->get();
        }
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        
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
        $activityLocations = Ports::where('company_id', Auth::user()->company_id)->get();
        
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
            $activityLocations = Ports::where('company_id', Auth::user()->company_id)->get();

        }

        $line = Lines::where('company_id', Auth::user()->company_id)->get();
        $transhipmentContainers = Containers::where('company_id', Auth::user()->company_id)->where(
            'is_transhipment',
            1
        )->get();

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
            'transhipmentContainers' => $transhipmentContainers,
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'voyage_id' => ['required'],
            'commodity_description' => ['required'],
            'bl_release' => ['required'],
            'customer_id' => ['required'],
            'containerDetails' => ['required'],
        ], [
            'containerDetails.required' => 'Container Details Cannot be empty',
        ]);

        $quotation = Quotation::find($request->quotation_id);
        $etaDate = VoyagePorts::where('voyage_id',$request->voyage_id)->where('port_from_name',$request->load_port_id)->pluck('eta')->first();

        if($booking->quotation_id != null){
            if($quotation->shipment_type == 'Export'){
                    if($etaDate <= $quotation->validity_from && $etaDate >= $quotation->validity_to){
                        return redirect()->back()->with('error','Invalid Date '.$etaDate.' Date Must Be Between '.$quotation->validity_from.' and '.$quotation->validity_to)
                        ->withInput($request->input());
                    }
            }
        }

    if($request->input('movement') == 'FCL/FCL'){
        $uniqueContainers = array();
        foreach ($request->containerDetails as $container) {
            if (!in_array(
                    $container['container_id'],
                    $uniqueContainers
                ) || $container['container_id'] == null || $container['container_id'] == "000") {
                if ($container['container_id'] != "000" || $container['container_id'] != null) {
                    array_push($uniqueContainers, $container['container_id']);
                }
            } else {
                return redirect()->back()->with('error', 'Container Numbers Must be unique')->withInput(
                    $request->input()
                );
            }
        }
    }
        $user = Auth::user();
        $ReferanceNumber = Booking::where('id', '!=', $booking->id)->where('company_id', $user->company_id)->where(
            'ref_no',
            $request->ref_no
        )->count();

        if ($ReferanceNumber > 0) {
            return back()->with('error', 'The Booking Refrance Number Already Exists');
        }

        $this->authorize(__FUNCTION__, Booking::class);
        $inputs = request()->all();
        unset($inputs['containerDetails'], $inputs['_token'], $inputs['removed']);
        $booking->update($inputs);
        BookingContainerDetails::destroy(explode(',', $request->removed));
        $booking->createOrUpdateContainerDetails($request->containerDetails);
        if ($request->hasFile('certificat')) {
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $booking->update(['certificat' => "certificat/" . $path]);
        }
        return redirect()->route('booking.index')->with('success', trans('Booking.Updated.Success'));
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

}
