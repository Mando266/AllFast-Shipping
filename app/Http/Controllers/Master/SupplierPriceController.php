<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\SupplierPrice;
use App\Models\Master\Ports;
use App\Models\Master\Lines;
use App\Models\Master\ContainersTypes;
use App\Filters\SupplierPrice\SupplierPriceIndexFilter;
use Illuminate\Support\Facades\Auth;

class SupplierPriceController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,SupplierPrice::class);

            $supplierPrice = SupplierPrice::where('company_id',Auth::user()->company_id)->filter(new SupplierPriceIndexFilter(request()))->orderBy('id','desc')->paginate(10);
            $equipment_types = ContainersTypes::orderBy('id')->get();
            $line = Lines::where('company_id',Auth::user()->company_id)->get();
            $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        
        return view('master.supplierPrice.index',[
            'items'=>$supplierPrice,
            'ports'=>$ports,
            'line'=>$line,
            'equipment_types'=>$equipment_types,
        ]); 
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,SupplierPrice::class);
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $line = Lines::where('company_id',Auth::user()->company_id)->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();

        return view('master.supplierPrice.create',[
            'ports'=>$ports,
            'line'=>$line,
            'equipment_types'=>$equipment_types,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,SupplierPrice::class);
        $user = Auth::user();
        $request->validate([
            'validity_from' => ['required'], 
            'validity_to' => ['required','after:validity_from'],
            'pol_id' => ['required'], 
            'pod_id' => ['required','different:pol_id'],

        ],[
            'validity_to.after'=>'Validaty To Should Be After Validaty From',
            'pod_id.different'=>'Pod The Same  Pol',
        ]);
        $supplierPrice = SupplierPrice::create($request->except('_token'));
        $supplierPrice->company_id = $user->company_id;
        $supplierPrice->save();
        return redirect()->route('supplierPrice.index')->with('success',trans('Supplier Price.created'));
    }
     
    public function show($id)
    {
        //
    }


    public function edit(SupplierPrice $supplierPrice)
    {
        $this->authorize(__FUNCTION__,SupplierPrice::class);
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $line = Lines::where('company_id',Auth::user()->company_id)->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();

        return view('master.supplierPrice.edit',[
            'supplierPrice'=>$supplierPrice,
            'ports'=>$ports,
            'line'=>$line,
            'equipment_types'=>$equipment_types,
        ]);
    }

    public function update(Request $request, SupplierPrice $supplierPrice)
    {
        $this->authorize(__FUNCTION__,SupplierPrice::class);
        $request->validate([
            'validity_from' => ['required'], 
            'validity_to' => ['required','after:validity_from'],
            'pol_id' => ['required'], 
            'pod_id' => ['required','different:pol_id'],
        ],[
            'validity_to.after'=>'Validaty To Should Be After Validaty From',
            'pod_id.different'=>'Pod The Same  Pol',
        ]);
        $supplierPrice->update($request->except('_token'));
        $supplierPrice->save();
        return redirect()->route('supplierPrice.index')->with('success',trans('Supplier Price.updated.success'));

    }

    public function destroy($id)
    {
        $supplierPrice = SupplierPrice::find($id);
        $supplierPrice->delete(); 
        return back()->with('success',trans('This Supplier Price.Deleted.success'));
    }
}
