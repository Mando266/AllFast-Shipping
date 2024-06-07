<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\Vessels;
use App\Models\Master\VesselType;
use App\Models\Master\VesselOperators;
use Illuminate\Http\Request;
use App\Filters\Voyages\VoyagesIndexFilter;
use App\Models\Master\Lines;
use Illuminate\Support\Facades\Auth;

class VesselsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Vessels::class);

            $vessels = Vessels::filter(new VoyagesIndexFilter(request()))->where('company_id',Auth::user()->company_id)->orderBy('id')->paginate(30);
            $vessel = Vessels::where('company_id',Auth::user()->company_id)->orderBy('name')->get();

        return view('master.vessels.index',[
            'items'=>$vessels,
            'vessel'=>$vessel,

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $countries = Country::orderBy('name')->get();
        $vessel_types = VesselType::orderBy('name')->get();
        $vesselOperators = Lines::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        return view('master.vessels.create',[
            'countries'=>$countries,
            'vessel_types'=>$vessel_types,
            'vesselOperators'=>$vesselOperators,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
        ]);
        $user = Auth::user();

        $NameDublicate  = Vessels::where('company_id',$user->company_id)->where('name',$request->name)->first();
            if($NameDublicate != null){
                return back()->with('alert','This Vessel Name Already Exists');
            }

        $CodeDublicate  = Vessels::where('company_id',$user->company_id)->where('code',$request->code)->first();
            if($CodeDublicate != null){
                return back()->with('alert','This Vessel Code Already Exists');
            }
         
        $CallSignDublicate  = Vessels::where('company_id',$user->company_id)->where('call_sign',$request->call_sign)->first();
            if($CallSignDublicate != null && $CallSignDublicate->call_sign != null ){
                return back()->with('alert','This Vessel Call Sign Already Exists');
            }

        $ImoNumberDublicate  = Vessels::where('company_id',$user->company_id)->where('imo_number',$request->imo_number)->first();
            if($ImoNumberDublicate != null && $ImoNumberDublicate->imo_number != null ){
                return back()->with('alert','This Vessel Imo Number Already Exists');
            }

        $vessels = Vessels::create($request->except('_token'));
        $vessels->company_id = $user->company_id;

        $vessels->save();
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $vessels->update(['certificat'=>"certificat/".$path]);
        }

        return redirect()->route('vessels.index')->with('success',trans('vessel.created'));
    }

    public function show(Vessels $vessel)
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $countries = Country::orderBy('name')->get();
        $vessel_types = VesselType::orderBy('name')->get();
        $vesselOperators = Lines::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        return view('master.vessels.show',[
            'vessel'=>$vessel,
            'countries'=>$countries,
            'vessel_types'=>$vessel_types,
            'vesselOperators'=>$vesselOperators,
        ]);
    }

    public function edit(Vessels $vessel)
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $countries = Country::orderBy('name')->get();
        $vessel_types = VesselType::orderBy('name')->get();
        $vesselOperators = Lines::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        return view('master.vessels.edit',[
            'vessel'=>$vessel,
            'countries'=>$countries,
            'vessel_types'=>$vessel_types,
            'vesselOperators'=>$vesselOperators,
        ]);
    }

    public function update(Request $request, Vessels $vessel)
    {
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
        ]);
        $user = Auth::user();

        $NameDublicate  = Vessels::where('id','!=',$vessel->id)->where('company_id',$user->company_id)->where('name',$request->name)->count();
            if($NameDublicate > 0){
                return back()->with('alert','This Vessel Name Already Exists');
            }

        $CodeDublicate  = Vessels::where('id','!=',$vessel->id)->where('company_id',$user->company_id)->where('code',$request->code)->count();
            if($CodeDublicate > 0){
                return back()->with('alert','This Vessel Code Already Exists');
            }
        
        $CallSignDublicate  = Vessels::where('id','!=',$vessel->id)->where('company_id',$user->company_id)->where('call_sign',$request->call_sign)->first();
            
            if($CallSignDublicate != null){
                if($CallSignDublicate->count() > 0){
                        if($CallSignDublicate->call_sign != null){
                            return back()->with('alert','This Vessel Call Sign Already Exists');
                        }
                    }
            }

        $ImoNumberDublicate  = Vessels::where('id','!=',$vessel->id)->where('company_id',$user->company_id)->where('imo_number',$request->imo_number)->first();
        if($ImoNumberDublicate != null){    
            if($ImoNumberDublicate->count() > 0  ){
                if($CallSignDublicate->imo_number != null ){
                    return back()->with('alert','This Vessel Imo Number Already Exists');
                }
            }
        }

        $this->authorize(__FUNCTION__,Vessels::class);
        $vessel->update($request->except('_token'));
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $vessel->update(['certificat'=>"certificat/".$path]);
        }
        return redirect()->route('vessels.index')->with('success',trans('vessel.updated.success'));
    }

    public function destroy($id)
    {
        $vessel =Vessels::Find($id);
        $vessel->delete();

        return redirect()->route('vessels.index')->with('success',trans('vessel.deleted.success'));
    }
}
