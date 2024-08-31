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
use App\Models\Containers\Triff;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class QuotationsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__, Quotation::class);
    
        return view('quotations.quotations.index', $this->getQuotationsData('Export'));
    }
    
    public function import()
    {
    
        return view('quotations.quotations.import', $this->getQuotationsData('Import'));
    }
    
    private function getQuotationsData($shipmentType)
    {

        $quotations = Quotation::filter(new QuotationIndexFilter(request()))
            ->where('company_id', Auth::user()->company_id)
            ->where('shipment_type', $shipmentType)
            ->with('quotationDesc')
            ->orderBy('id', 'desc')
            ->paginate(30);
    
        $exportQuotations = request();
        $quotation = Quotation::where('company_id', Auth::user()->company_id)
        ->where('shipment_type', $shipmentType)->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();
     
        return [
            'items' => $quotations,
            'exportQuotations' => $exportQuotations,
            'quotation' => $quotation,
            'ports' => $ports,
            'customers' => $customers,
        ];
    }
    
    public function create()
    {
        $this->authorize(__FUNCTION__, Quotation::class);
    
        return view('quotations.quotations.create', $this->getQuotationCreateData());
    }
    
    public function importcreate()
    {
    
        return view('quotations.quotations.importcreate', $this->getQuotationCreateData());
    }
    
    private function getQuotationCreateData()
    {
        $user = Auth::user();
        $company_id = $user->company_id;
        
        $paymentLocation = Ports::orderBy('id')->get();
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $currency = Currency::where('name', '!=', 'EGP')->orderBy('id')->get();
        $triffs = Triff::get();
    
        $customers = Customers::where('company_id', $company_id)
            ->orderBy('id')
            ->with('CustomerRoles.role')
            ->get();    
            
        $ffw = Customers::where('company_id', $company_id)
            ->whereHas('CustomerRoles', function ($query) {
                $query->where('role_id', 6);
            })
            ->with('CustomerRoles.role')
            ->get();
            
        $consignee = Customers::where('company_id', $company_id)
            ->whereHas('CustomerRoles', function ($query) {
                $query->where('role_id', 2);
            })
            ->with('CustomerRoles.role')
            ->get();
    
        $country = Country::orderBy('name')->get();
        
        $principals = Lines::where('company_id', $company_id)
            ->whereHas('types', function ($query) {
                $query->whereIn('type_id', [5, 7, 9]);
            })
            ->get();
            
        $operators = Lines::where('company_id', $company_id)
            ->whereHas('types', function ($query) {
                $query->whereIn('type_id', [4, 2, 8]);
            })
            ->get();
            
        $agents = [];
    
        return [
            'user' => $user,
            'paymentLocation' => $paymentLocation,
            'ports' => [],
            'agents' => $agents,
            'equipment_types' => $equipment_types,
            'currency' => $currency,
            'triffs' => $triffs,
            'customers' => $customers,
            'ffw' => $ffw,
            'country' => $country,
            'principals' => $principals,
            'operators' => $operators,
            'consignee' => $consignee,
        ];
    }
    
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'validity_from' => ['required'],
            'customer_id' => ['required'],
            'load_port_id' => ['required'],
            'discharge_port_id' => ['required', 'different:load_port_id'],
            'validity_to' => ['required', 'after:validity_from'],
            'commodity_des' => ['required'],
        ], [
            'validity_to.after' => 'Validity To Should Be After Validity From',
            'discharge_port_id.different' => 'Load Port cannot be the same as Discharge Port',
        ]);
    
        $user = Auth::user();
        $agent_id = $user->agent_id;  
        $validityFrom = Carbon::parse($request->validity_from)->format('d-m-Y');
        $placeOfAcceptance = Ports::where('id', $request->place_of_acceptence_id)->pluck('code')->first();
        $placeOfAcceptance = substr($placeOfAcceptance, -3);
        $placeOfDelivery = Ports::where('id', $request->place_of_delivery_id)->pluck('code')->first();
        $placeOfDelivery = substr($placeOfDelivery, -3);
        $customerName = Customers::where('id', $request->customer_id)->pluck('name')->first();
        $customerName = substr($customerName, 0, 8);
        // Generate reference number
        $refNo = $placeOfAcceptance . $placeOfDelivery . '-' . $customerName . '-' . $validityFrom . '/';
    
        $shipment_type = '';    
        if ($request->input('shipment_type') == 'Export') {
            $shipment_type = 'Export';
        }
        if ($request->input('shipment_type') == 'Import') {
            $shipment_type = 'Import';
        }    
        
        $quotations = Quotation::where("customer_id", $request->input('customer_id'))
            ->where("load_port_id", $request->input('load_port_id'))
            ->where("discharge_port_id", $request->input('discharge_port_id'))
            ->where("oog_dimensions", $request->input('oog_dimensions'))
            ->get();
    
        if ($quotations->count() > 0 && $request->input('validity_from') < $quotations[0]->validity_to) {
            return redirect()->back()->with('error', 'This quotation is duplicated with the same user in the same time');
        }
    
        $quotation = Quotation::create([
            'ref_no' => "",
            'discharge_agent_id' => $user->agent_id,
            'company_id' => $user->company_id,
            'quoted_by_id' => $user->id,
            'agent_id' => $request->input('agent_id'),
            'countryload' => $request->input('countryload'),
            'countrydis' => $user->agent->country_id,
            'validity_from' => $request->input('validity_from'),
            'validity_to' => $request->input('validity_to'),
            'customer_id' => $request->input('customer_id'),
            'ffw_id' => $request->input('ffw_id'),
            'place_of_acceptence_id' => $request->input('place_of_acceptence_id'),
            'place_of_delivery_id' => $request->input('place_of_delivery_id'),
            'load_port_id' => $request->input('load_port_id'),
            'discharge_port_id' => $request->input('discharge_port_id'),
            'place_return_id' => $request->input('place_return_id'),
            'oog_dimensions' => $request->input('oog_dimensions'),
            'commodity_code' => $request->input('commodity_code'),
            'commodity_des' => $request->input('commodity_des'),
            'pick_up_location' => $request->input('pick_up_location'),
            'payment_kind' => $request->input('payment_kind'),
            'quotation_type' => $request->input('quotation_type'),
            'transportation_mode' => $request->input('transportation_mode'),
            'status' => "pending",
            'shipment_type' => $shipment_type,
            'principal_name' => $request->input('principal_name'),
            'vessel_name' => $request->input('vessel_name'),
            'booking_agency' => $agent_id,
            'operator_frieght_payment' => $request->input('operator_frieght_payment'),
            'slot_rate'=> $request->input('slot_rate'),
            'payment_location' => $request->input('payment_location'),
            'customer_consignee_id' => $request->input('customer_consignee_id'),
            'tariff_type' => $request->input('tariff_type'),
        ]);
    
        // Update reference number with the newly created quotation ID
        $quotation->ref_no = $refNo . $quotation->id;
        $quotation->save();
    
        // Create quotation details
        foreach ($request->input('quotationDis', []) as $quotationDis) {
            QuotationDes::create([
                'quotation_id' => $quotation->id,
                'ofr' => $quotationDis['ofr'],
                'currency' => $quotationDis['currency'],
                'equipment_type_id' => $quotationDis['equipment_type_id'],
                'request_type' => $quotationDis['request_type'],
                'free_time' => $quotationDis['free_time'],
                'thc_payment' => $quotationDis['thc_payment'],
                'soc' => $quotationDis['soc'] ?? 0,
                'imo' => $quotationDis['imo'] ?? 0,
                'oog' => $quotationDis['oog'] ?? 0,
                'rf' => $quotationDis['rf'] ?? 0,
                'nor' => $quotationDis['nor'] ?? 0,
            ]);
        }  
        $route = $quotation->shipment_type == "Export" ? 'quotations.index' : 'quotation.import';
        return redirect()->route($route)->with('success', trans('Quotation.Created'));        
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
        $ports = Ports::orderBy('id')->get();
        $paymentLocation = Ports::orderBy('id')->get();
        $container_types = ContainersTypes::orderBy('id')->get();
        $currency = Currency::where('name','!=','EGP')->orderBy('id')->get();
        $customers = Customers::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $triffs = Triff::get();
        $country = Country::orderBy('name')->get();
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $principals = Lines::where('company_id', Auth::user()->company_id)
        ->whereHas('types', function ($query) {
            return $query->whereIn('type_id', [5, 7, 9]);
        })->get();
        $operators = Lines::where('company_id', Auth::user()->company_id)
        ->whereHas('types', function ($query) {
            return $query->whereIn('type_id', [4, 2 , 8]);
        })->get();
        $ffw = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();

        $agents = Agents::where('company_id', Auth::user()->company_id)->where('is_active', 1)->get();

        $consignee = Customers::where('company_id', Auth::user()->company_id)->whereHas(
            'CustomerRoles',
            function ($query) {
                return $query->where('role_id', 2);
            }
        )->with('CustomerRoles.role')->get();
      
        $user = Auth::user();
        return view('quotations.quotations.edit', [
            'user' => $user,
            'quotation' => $quotation,
            'ports' => $ports,
            'paymentLocation' => $paymentLocation,
            'agents' => $agents,
            'container_types' => $container_types,
            'currency' => $currency,
            'customers' => $customers,
            'ffw' => $ffw,
            'principals' => $principals,
            'operators'  => $operators,
            'equipment_types' => $equipment_types,
            'country' => $country,
            'consignee' => $consignee,
            'triffs' => $triffs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'validity_from' => ['required'],
            'customer_id' => ['required'],
            'load_port_id' => ['required'],
            'discharge_port_id' => ['required', 'different:load_port_id'],
            'validity_to' => ['required', 'after:validity_from'],
            'commodity_des' => ['required'],
            'quotationDis.*.free_time' => ['required'],
        ], [
            'validity_to.after' => 'Validity To Should Be After Validity From',
            'discharge_port_id.different' => 'Load Port cannot be the same as Discharge Port',
            'quotationDis.*.free_time.required' => 'Free Time is required for each equipment type',
        ]);

        $this->authorize(__FUNCTION__, Quotation::class);
        $user = Auth::user();
        $agent_id = $user->agent_id;  
        $quotation = Quotation::with('quotationDesc', 'quotationLoad')->find($id);
   
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
            'place_return_id' => $request->place_return_id,
            'commodity_code' => $request->commodity_code,
            'commodity_des' => $request->commodity_des,
            'pick_up_location' => $request->pick_up_location,
            'agent_id' => $request->agent_id,
            'oog_dimensions' => $request->oog_dimensions,
            'payment_kind' => $request->payment_kind,
            'quotation_type' => $request->quotation_type,
            'transportation_mode' => $request->transportation_mode,
            'booking_agency' => $agent_id,
            'operator_frieght_payment' => $request->operator_frieght_payment,
            'slot_rate'=> $request->slot_rate,
            'payment_location' => $request->payment_location,
            'tariff_type' => $request->tariff_type,
            'customer_consignee_id'=>$request->customer_consignee_id,
        ];
        
        // Update the quotation
        $quotation->update($input);
        // Handle Remove
        if ($request->filled('removedDesc')) {
        QuotationDes::destroy(explode(',', $request->removedDesc));
        }

        $quotation->createOrUpdateDesc($request->quotationDis);
        $route = $quotation->shipment_type == "Export" ? 'quotations.index' : 'quotation.import';
        return redirect()->route($route)->with('success', trans('Quotation.Updated.Success'));        
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
