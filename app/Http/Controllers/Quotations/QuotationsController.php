<?php

namespace App\Http\Controllers\Quotations;

use App\Filters\Quotation\QuotationIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Master\Agents;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Country;
use App\Models\Master\Currency;
use App\Models\Master\Customers;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Quotations\Quotation;
use App\Models\Quotations\QuotationDes;
use App\Models\Quotations\QuotationLoad;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class QuotationsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__, Quotation::class);

        $quotations = Quotation::filter(new QuotationIndexFilter(request()))->where('company_id', Auth::user()->company_id)->with('quotationDesc')->orderBy('id', 'desc')->paginate(30);
        //dd($quotations);
        $exportQuotations = request();
        $quotation = Quotation::where('company_id', Auth::user()->company_id)->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();

//        dd(\request());
        return view('quotations.quotations.index', [
            'items' => $quotations,
            'exportQuotations' => $exportQuotations,
            'quotation' => $quotation,
            'ports' => $ports,
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__, Quotation::class);
        $paymentLocation = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $ports = [];
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $currency = Currency::orderBy('id')->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->orderBy('id')->with('CustomerRoles.role')->get();
        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $consignee = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 2);
            }
        )->with('CustomerRoles.role')->get();
        $country = Country::orderBy('name')->get();
        $principals = Lines::where('company_id', Auth::user()->company_id)
        ->whereHas('types', function ($query) {
            return $query->whereIn('type_id', [5, 7, 9]);
        })->get();
        $oprators = Lines::where('company_id', Auth::user()->company_id)
        ->whereHas('types', function ($query) {
            return $query->whereIn('type_id', [4, 2, 8]);
        })->get();
        $booking_agency = Agents::where('id',Auth::user()->agent_id)->get();

        $isSuperAdmin = false;
        if (Auth::user()->is_super_admin) {
            $isSuperAdmin = true;
            // $agents = AppUser::role('agent');
            $agents = [];
        } else {
            $agents = [];
        }
        $user = Auth::user();
        return view('quotations.quotations.create', [
            'user' => $user,
            'paymentLocation' => $paymentLocation,
            'ports' => $ports,
            'isSuperAdmin' => $isSuperAdmin,
            'agents' => $agents,
            'equipment_types' => $equipment_types,
            'currency' => $currency,
            'customers' => $customers,
            'ffw' => $ffw,
            'country' => $country,
            'principals' => $principals,
            'oprators'  => $oprators,
            'booking_agency' => $booking_agency,
            'consignee' => $consignee,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'validity_from' => ['required'],
            'customer_id' => ['required'],
            // 'place_of_acceptence_id' => ['required'],
            'load_port_id' => ['required'],
            // 'export_detention' => ['required'],
            // 'import_detention' => ['required'],
            // 'place_of_delivery_id' => ['required', 'different:place_of_acceptence_id'],
            'discharge_port_id' => ['required', 'different:load_port_id'],
            'validity_to' => ['required', 'after:validity_from'],
            'commodity_des' => ['required'],
        ], [
            'validity_to.after' => 'Validaty To Should Be After Validaty From ',
            // 'place_of_delivery_id.different' => 'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different' => 'Load Port The Same  Discharge Port',
        ]);

        $user = Auth::user();
        $validityFrom = Carbon::parse($request->validity_from)->format('d-m-Y');
        // $validityFrom = str_replace('-', '', $validityFrom);
        $placeOfAcceptance = Ports::where('id', $request->place_of_acceptence_id)->pluck('code')->first();
        $placeOfAcceptance = substr($placeOfAcceptance, -3);
        $placeOfDelivery = Ports::where('id', $request->place_of_delivery_id)->pluck('code')->first();
        $placeOfDelivery = substr($placeOfDelivery, -3);
        $customerName = Customers::where('id', $request->customer_id)->pluck('name')->first();
        $customerName = substr($customerName, 0, 8);
        $refNo = $placeOfAcceptance . $placeOfDelivery . '-' . $customerName . '-' . $validityFrom . '/';
       
        $portOfLoad = Ports::find($request->input('load_port_id'));
        $portOfDischarge = Ports::find($request->input('discharge_port_id'));
        $shipment_type = '';
        //check if port of load in egypt to make shipment type export or import
        if ($portOfLoad->country_id == 61) {
            $shipment_type = 'Export';
        }
        if ($portOfDischarge->country_id == 61) {
            $shipment_type = 'Import';
        }
        if ($request->input('transportation_mode') == 'trucker') {
            $shipment_type = 'trucking';
        }
        if (isset($request->agent_id)) {
            $oldQuotations =
                Quotation::where("customer_id", $request->input('customer_id'))
                    ->where("place_of_acceptence_id", $request->input('place_of_acceptence_id'))
                    ->where("place_of_delivery_id", $request->input('place_of_delivery_id'))
                    ->where("load_port_id", $request->input('load_port_id'))
                    ->where("discharge_port_id", $request->input('discharge_port_id'))
                    ->where("place_return_id", $request->input('place_return_id'))
                    ->where("equipment_type_id", $request->input('equipment_type_id'))
                    ->where("export_detention", $request->input('export_detention'))
                    ->where("import_detention", $request->input('import_detention'))
                    ->where("export_storage", $request->input('export_storage'))
                    ->where("import_storage", $request->input('import_storage'))
                    ->where("oog_dimensions", $request->input('oog_dimensions'))
                    ->get();

            if ($oldQuotations->count() > 0) {
                if ($request->input('validity_from') < $oldQuotations[0]->validity_to) {
                    return redirect()->back()->with('error', 'this quotation is dublicated with the same user in the same time');
                }
            }
            //  $request->agent_id ==> Import Agent  ,  $request->discharge_agent_id   ==> Discharge Agent

            $quotations = Quotation::create([
                'ref_no' => $request->input('ref_no'),
                // 'ref_no' => "",
                'agent_id' => $request->agent_id,
                'quoted_by_id' => $user->id,
                'company_id' => $user->company_id,
                'discharge_agent_id' => $request->input('discharge_agent_id'),
                'countryload' => $request->input('countryload'),
                'countrydis' => $request->input('countrydis'),
                'vessel_name' => $request->input('vessel_name'),
                'principal_name' => $request->input('principal_name'),
                'validity_from' => $request->input('validity_from'),
                'validity_to' => $request->input('validity_to'),
                'soc' => $request->soc ? $request->soc : 0,
                'imo' => $request->imo ? $request->imo : 0,
                'oog' => $request->oog ? $request->oog : 0,
                'rf' => $request->rf ? $request->rf : 0,
                'nor' => $request->nor ? $request->nor : 0,
                'customer_id' => $request->input('customer_id'),
                'ffw_id' => $request->input('ffw_id'),
                'place_of_acceptence_id' => $request->input('place_of_acceptence_id'),
                'place_of_delivery_id' => $request->input('place_of_delivery_id'),
                'load_port_id' => $request->input('load_port_id'),
                'discharge_port_id' => $request->input('discharge_port_id'),
                'place_return_id' => $request->input('place_return_id'),
                'export_detention' => $request->input('export_detention'),
                'import_detention' => $request->input('import_detention'),
                'export_storage' => $request->input('export_storage'),
                'import_storage' => $request->input('import_storage'),
                'oog_dimensions' => $request->input('oog_dimensions'),
                'commodity_code' => $request->input('commodity_code'),
                'commodity_des' => $request->input('commodity_des'),
                'pick_up_location' => $request->input('pick_up_location'),
                'show_import' => $request->input('show_import'),
                'power_charges' => $request->input('power_charges'),
                'payment_kind' => $request->input('payment_kind'),
                'quotation_type' => $request->input('quotation_type'),
                'transportation_mode' => $request->input('transportation_mode'),
                'status' => "pending",
                'shipment_type' => $shipment_type,
                'booking_agency' => $request->input('booking_agency'),
                'agency_bookingr_ref' => $request->input('agency_bookingr_ref'),
                'operator_frieght_payment' => $request->input('operator_frieght_payment'),
                'payment_location' => $request->input('payment_location'),
                'customer_consignee_id'=>$request->input('customer_consignee_id'),
            ]);
            $refNo .= $quotations->id;
            $quotations->ref_no = $refNo;
            $quotations->save();
        } else {
            $quotations = Quotation::create([
                'ref_no' => $request->input('ref_no'),
                // 'ref_no' => "",
                'discharge_agent_id' => $user->agent_id,
                'company_id' => $user->company_id,
                'quoted_by_id' => $user->id,
                'agent_id' => $request->input('discharge_agent_id'),
                'countryload' => $request->input('countryload'),
                'countrydis' => $user->agent->country_id,
                'validity_from' => $request->input('validity_from'),
                'validity_to' => $request->input('validity_to'),
                'soc' => $request->soc ? $request->soc : 0,
                'imo' => $request->imo ? $request->imo : 0,
                'oog' => $request->oog ? $request->oog : 0,
                'rf' => $request->rf ? $request->rf : 0,
                'nor' => $request->nor ? $request->nor : 0,
                'customer_id' => $request->input('customer_id'),
                'ffw_id' => $request->input('ffw_id'),
                'place_of_acceptence_id' => $request->input('place_of_acceptence_id'),
                'place_of_delivery_id' => $request->input('place_of_delivery_id'),
                'load_port_id' => $request->input('load_port_id'),
                'discharge_port_id' => $request->input('discharge_port_id'),
                'place_return_id' => $request->input('place_return_id'),
                'export_detention' => $request->input('export_detention'),
                'import_detention' => $request->input('import_detention'),
                'export_storage' => $request->input('export_storage'),
                'import_storage' => $request->input('import_storage'),
                'oog_dimensions' => $request->input('oog_dimensions'),
                'commodity_code' => $request->input('commodity_code'),
                'commodity_des' => $request->input('commodity_des'),
                'pick_up_location' => $request->input('pick_up_location'),
                'show_import' => $request->input('show_import'),
                'power_charges' => $request->input('power_charges'),
                'payment_kind' => $request->input('payment_kind'),
                'quotation_type' => $request->input('quotation_type'),
                'transportation_mode' => $request->input('transportation_mode'),
                'status' => "pending",
                'shipment_type' => $shipment_type,
                'booking_agency' => $request->input('booking_agency'),
                'agency_bookingr_ref' => $request->input('agency_bookingr_ref'),
                'operator_frieght_payment' => $request->input('operator_frieght_payment'),
                'payment_location' => $request->input('payment_location'),
                'customer_consignee_id'=>$request->input('customer_consignee_id'),
            ]);
            // $refNo .= $quotations->id;
            // $quotations->ref_no = $refNo;
            // $quotations->save();
        }

        foreach ($request->input('quotationDis', []) as $quotationDis) {
            QuotationDes::create([
                'quotation_id' => $quotations->id,
                'ofr' => $quotationDis['ofr'],
                'currency' => $quotationDis['currency'],
                'equipment_type_id' => $quotationDis['equipment_type_id'],
            ]);
        }

        return redirect()->route('quotations.index')->with('success', trans('Quotation.created'));
    }

    public function show($id)
    {
        $quotation = Quotation::with('quotationDesc', 'quotationLoad', 'customer.CustomerRoles.role')->find($id);
        return view('quotations.quotations.show', [
            'quotation' => $quotation,
        ]);
    }

    public function edit($id)
    {
        $this->authorize(__FUNCTION__, Quotation::class);
        $quotation = Quotation::with('quotationDesc', 'quotationLoad')->find($id);
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $paymentLocation = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $container_types = ContainersTypes::orderBy('id')->get();
        $currency = Currency::orderBy('id')->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $country = Country::orderBy('name')->get();
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $principals = Lines::where('company_id', Auth::user()->company_id)
        ->whereHas('types', function ($query) {
            return $query->whereIn('type_id', [5, 7, 9]);
        })->get();
        $oprators = Lines::where('company_id', Auth::user()->company_id)
        ->whereHas('types', function ($query) {
            return $query->whereIn('type_id', [4, 2 , 8]);
        })->get();
        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();

        $booking_agency = Agents::where('id',Auth::user()->agent_id)->get();
        
        $consignee = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 2);
            }
        )->with('CustomerRoles.role')->get();
        $isSuperAdmin = false;
        if (Auth::user()->is_super_admin) {
            $isSuperAdmin = true;
            // $agents = AppUser::role('agent');
            $agents = Agents::where('company_id', Auth::user()->company_id)->where('is_active', 1)->get();
        } else {
            $agents = [];
        }
        $user = Auth::user();
        return view('quotations.quotations.edit', [
            'user' => $user,
            'quotation' => $quotation,
            'ports' => $ports,
            'paymentLocation' => $paymentLocation,
            'isSuperAdmin' => $isSuperAdmin,
            'agents' => $agents,
            'container_types' => $container_types,
            'currency' => $currency,
            'customers' => $customers,
            'ffw' => $ffw,
            'principals' => $principals,
            'oprators'  => $oprators,
            'equipment_types' => $equipment_types,
            'country' => $country,
            'booking_agency' => $booking_agency,
            'consignee' => $consignee,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'validity_from' => ['required'],
            'customer_id' => ['required'],
            // 'place_of_acceptence_id' => ['required'],
            'load_port_id' => ['required'],
            'equipment_type_id' => ['required'],
            // 'export_detention' => ['required'],
            // 'import_detention' => ['required'],
            // 'place_of_delivery_id' => ['required', 'different:place_of_acceptence_id'],
            'discharge_port_id' => ['required', 'different:load_port_id'],
            'validity_to' => ['required', 'after:validity_from'],
            'commodity_des' => ['required'],
        ], [
            'validity_to.after' => 'Validaty To Should Be After Validaty From ',
            // 'place_of_delivery_id.different' => 'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different' => 'Load Port The Same  Discharge Port',
        ]);


        $this->authorize(__FUNCTION__, Quotation::class);
        $user = Auth::user();
        $quotation = Quotation::with('quotationDesc', 'quotationLoad')->find($id);
        // dd($request->removedDesc);
        // dd($quotation->discharge_agent_id != $request->discharge_agent_id || $request->equipment_type_id != $quotation->equipment_type_id);
        // dd($request->input());
        $input = [ 
            'ref_no' => $request->ref_no,
            'validity_from' => $request->validity_from,
            'validity_to' => $request->validity_to,
            'customer_id' => $request->customer_id,
            'ffw_id' => $request->ffw_id,
            'countryload' => $request->countryload,
            'countrydis' => $request->countrydis,
            'place_of_acceptence_id' => $request->place_of_acceptence_id,
            'place_of_delivery_id' => $request->place_of_delivery_id,
            'load_port_id' => $request->load_port_id,
            'discharge_port_id' => $request->discharge_port_id,
            'equipment_type_id' => $request->equipment_type_id,
            'place_return_id' => $request->place_return_id,
            'export_detention' => $request->export_detention,
            'import_detention' => $request->import_detention,
            'export_storage' => $request->export_storage,
            'import_storage' => $request->import_storage,
            'commodity_code' => $request->commodity_code,
            'commodity_des' => $request->commodity_des,
            'pick_up_location' => $request->pick_up_location,
            'ofr' => $request->ofr,
            'soc' => $request->soc ? $request->soc : 0,
            'imo' => $request->imo ? $request->imo : 0,
            'oog' => $request->oog ? $request->oog : 0,
            'rf' => $request->rf ? $request->rf : 0,
            'nor' => $request->nor ? $request->nor : 0,
            'show_import' => $request->show_import,
            'agent_id' => $request->agent_id,
            'oog_dimensions' => $request->oog_dimensions,
            'power_charges' => $request->power_charges,
            'payment_kind' => $request->payment_kind,
            'quotation_type' => $request->quotation_type,
            'transportation_mode' => $request->transportation_mode,
            'booking_agency' => $request->booking_agency,
            'agency_bookingr_ref' => $request->agency_bookingr_ref,
            'operator_frieght_payment' => $request->operator_frieght_payment,
            'payment_location' => $request->payment_location,
            'customer_consignee_id'=>$request->input('customer_consignee_id'),
        ];
        if ($user->is_super_admin) {
            if ($quotation->discharge_agent_id != $request->discharge_agent_id || $request->equipment_type_id != $quotation->equipment_type_id) {
                $deleteOldDes = QuotationDes::where('quotation_id', $quotation->id)->get();
                foreach ($deleteOldDes as $x) {
                    $x->delete();
                }
            }
            if ($quotation->agent_id != $request->agent_id || $request->equipment_type_id != $quotation->equipment_type_id) {
                $deleteOldLoad = QuotationLoad::where('quotation_id', $quotation->id)->get();
                foreach ($deleteOldLoad as $y) {
                    $y->delete();
                }
            }
            $input ['vessel_name'] = $request->vessel_name;
            $input ['principal_name'] = $request->principal_name;
        } else {
            if ($request->equipment_type_id != $quotation->equipment_type_id) {
                $deleteOldDes = QuotationDes::where('quotation_id', $quotation->id)->get();
                foreach ($deleteOldDes as $x) {
                    $x->delete();
                }
            }
            if ($request->equipment_type_id != $quotation->equipment_type_id) {
                $deleteOldLoad = QuotationLoad::where('quotation_id', $quotation->id)->get();
                foreach ($deleteOldLoad as $y) {
                    $y->delete();
                }
            }
        }

        $quotation->createOrUpdateDesc($request->quotationDis);
        // dd($quotation);


        $quotation->createOrUpdateLoad($request->quotationLoad);
        // dd($user);
        if (isset($request->discharge_agent_id)) {
            $input ['discharge_agent_id'] = $request->discharge_agent_id;
            $input ['status'] = "MSL count";
            $quotation->update($input);
        } else {
            if ($quotation->status == 'pending') {
                // dd($user);
                $input ['status'] = "pending";
                $quotation->update($input);
            } else {
                $input ['status'] = "Agent count";
                $quotation->update($input);
            }
        }

        QuotationDes::destroy(explode(',', $request->removedDesc));
        QuotationLoad::destroy(explode(',', $request->removedLoad));
        return redirect()->route('quotations.index')->with('success', trans('Quotation.updated.success'));
    }

    public function approve($id)
    {
        $quotation = Quotation::findOrFail($id);

        if ($quotation) {
            $quotation->status = "approved";
            $quotation->save();
        }
        return back()->with('success', "$quotation->name approved Successfully");
    }

    public function reject($id)
    {
        $quotation = Quotation::findOrFail($id);

        if ($quotation) {
            $quotation->status = "rejected";
            $quotation->save();
        }
        return back()->with('success', "$quotation->name rejected Successfully");
    }

    public function destroy($id)
    {
        $quotation = Quotation::find($id);
        QuotationDes::where('quotation_id', $id)->delete();
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', trans('Quotation.deleted.success'));
    }
}
