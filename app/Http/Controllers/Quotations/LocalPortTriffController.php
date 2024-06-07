<?php

namespace App\Http\Controllers\Quotations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotations\LocalPortTriff;
use App\Models\Quotations\LocalPortTriffDetailes;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Terminals;
use App\Models\Master\Country;
use Illuminate\Support\Carbon;
use App\Filters\Quotation\QuotationIndexFilter;
use App\Models\Master\Currency;
use App\Models\Master\Agents;
use App\Models\Invoice\ChargesDesc;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LocalPortTriffController extends Controller
{


    public function __construct( LocalPortTriffDetailes $localPortTriffDetailes )
    {
        $this->localPortTriffDetailes = $localPortTriffDetailes;
    }

    public function index()
    {
        $this->authorize(__FUNCTION__,LocalPortTriff::class);

            $localporttriff = LocalPortTriff::where('company_id',Auth::user()->company_id)->filter(new QuotationIndexFilter(request()))
                ->with('triffPriceDetailes.charge')->orderBy('id','desc')->paginate(30);
            $localport = LocalPortTriff::where('company_id',Auth::user()->company_id)->get();
            
            return view('quotations.localporttriff.index',[
                'items'=>$localporttriff,
                'localport'=>$localport,
                ]); 
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,LocalPortTriff::class);
            $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $equipment_types = ContainersTypes::orderBy('id')->get();
            $terminals = Terminals::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $country = Country::orderBy('id')->get();
            $currency = Currency::all();
            $agents = Agents::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $charges = ChargesDesc::orderBy('id')->get();

            return view('quotations.localporttriff.create',[
                'ports'=>$ports,
                'equipment_types'=>$equipment_types,
                'terminals'=>$terminals,
                'country'=>$country,
                'currency'=>$currency,
                'agents'=>$agents,
                'charges'=>$charges,
            ]);

    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'country_id' => ['required'],  
            'port_id' => ['required'], 
            'terminal_id' => ['required'], 
            'agent_id' => ['required'], 
            'validity_from' => ['required'], 
            'validity_to' => ['required','after:validity_from'],
        ],[
            'validity_to.after'=>'Validaty To Should Be After Validaty From ',
        ]);

        $country = Country::where('id',$request->country_id)->pluck('prefix')->first();
        $port = Ports::where('id',$request->port_id)->pluck('code')->first();
        $validityFrom = Carbon::parse($request->validity_from)->format('d-m-Y');
        $validityTo = Carbon::parse($request->validity_to)->format('d-m-Y');
        $triff_no = $country.'-'.$port.'-'.$request->agent_id.'-'.''.$validityFrom.'.'.'To'.'.'.$validityTo.'-';
        $localporttriff = LocalPortTriff::create([
            'triff_no'=> "",
            'country_id'=> $request->input('country_id'),
            'port_id'=> $request->input('port_id'),
            'terminal_id'=> $request->input('terminal_id'),
            'validity_from'=> $request->input('validity_from'),
            'validity_to'=> $request->input('validity_to'),
            'agent_id'=> $request->input('agent_id'),
            'company_id'=>$user->company_id,
        ]);

        foreach($request->input('triffPriceDetailes') as $triffPriceDetailes){
                LocalPortTriffDetailes::create([
                'quotation_triff_id'=>$localporttriff->id,
                'charge_type'=>$triffPriceDetailes['charge_type'],
                'unit'=>$triffPriceDetailes['unit'],
                'selling_price'=>$triffPriceDetailes['selling_price'],
                'cost'=>$triffPriceDetailes['cost'],
                'agency_revene'=>$triffPriceDetailes['agency_revene'],
                'liner'=>$triffPriceDetailes['liner'],
                'payer'=>$triffPriceDetailes['payer'],
                'add_to_quotation' => $triffPriceDetailes['add_to_quotation'],
                'currency'=> $triffPriceDetailes['currency'],
                'equipment_type_id'=> $triffPriceDetailes['equipment_type_id'],
                'is_import_or_export'=> $triffPriceDetailes['is_import_or_export'],
                'standard_or_customise'=>$triffPriceDetailes['standard_or_customise'],
            ]);
            $triff_no .=$localporttriff->id;
            $localporttriff->triff_no = $triff_no;
            $localporttriff->save();
        }
        return redirect()->route('localporttriff.index')->with('success',trans('Local Port Triff.Created'));
    }

    public function show($id)
    {
        $this->authorize(__FUNCTION__,LocalPortTriff::class);
        $localporttriff = LocalPortTriff::find($id);
        $triffPriceDetailes = LocalPortTriffDetailes::where('quotation_triff_id',$id)->get();
        $TriffNo = LocalPortTriff::where('id',$id)->select('triff_no')->first();
        session()->flash('TriffNo',$TriffNo);
        session()->flash('triffPriceDetailes',$triffPriceDetailes);
        // dd($triffPriceDetailes);
        return view('quotations.localporttriff.show',[
            'localporttriff'=>$localporttriff,
            'triffPriceDetailes'=>$triffPriceDetailes,
            'TriffNo'=>$TriffNo,
        ]);    
    }

    public function edit($id)
    {
        $this->authorize(__FUNCTION__,LocalPortTriff::class);
        $localporttriff = LocalPortTriff::where('company_id',Auth::user()->company_id)->with('triffPriceDetailes')->find($id);
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $terminals = Terminals::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $country = Country::orderBy('id')->get();
        $currency = Currency::all();
        $charges = ChargesDesc::orderBy('id')->get();
        $agents = Agents::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        
        return view('quotations.localporttriff.edit',[
            'localporttriff'=>$localporttriff,
            'ports'=>$ports,
            'equipment_types'=>$equipment_types,
            'terminals'=>$terminals,
            'country'=>$country,
            'currency'=>$currency,
            'agents'=>$agents,
            'charges'=>$charges,
        ]);
    }

    public function update($id)
    {
        $inputs = request()->all();
        unset($inputs['triffPriceDetailes'],$inputs['removed']);
        $localPortTriff = LocalPortTriff::find($id);
        $localPortTriff->update($inputs);
        LocalPortTriffDetailes::destroy(explode(',',request()->removed));
        $localPortTriff->createOrUpdateDetailes(request()->triffPriceDetailes);

        return redirect()->route('localporttriff.index')->with('success',trans('Local Port Triff.Update.Success'));
    }

    public function destroy($id)
    {
        $localporttriff = LocalPortTriff::find($id);
        LocalPortTriffDetailes::where('quotation_triff_id',$id)->delete();
        $localporttriff->delete();
        return redirect()->route('localporttriff.index')->with('success',trans('Local Port Triff.Deleted.Success'));
    }
}
