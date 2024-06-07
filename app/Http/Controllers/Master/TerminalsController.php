<?php

namespace App\Http\Controllers\Master;

use App\Filters\Voyages\VoyagesIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\Terminals;
use App\Models\Master\Ports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TerminalsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Terminals::class);

            $terminals = Terminals::filter(new VoyagesIndexFilter(request()))->orderBy('id')->paginate(10);
            $port = Ports::orderBy('id')->get();   
        
        return view('master.terminals.index',[
                'items'=>$terminals,
                'port'=>$port,
        ]);    
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $countries = Country::orderBy('name')->get();
        $ports = [];
        return view('master.terminals.create',[
            'countries'=>$countries,
            'ports'=>$ports,
        ]); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
        ]);
        $user = Auth::user();

        $CodeDublicate  = Terminals::where('code',$request->code)->first();
        if($CodeDublicate != null){
            return back()->with('alert','This Terminal Code Already Exists');
        }

        $NameDublicate  = Terminals::where('name',$request->name)->first();
        if($NameDublicate != null){
            return back()->with('alert','This Terminal Name Already Exists');
        }

        $terminals = Terminals::create($request->except('_token'));
        $terminals->company_id = $user->company_id;
        $terminals->save();
        return redirect()->route('terminals.index')->with('success',trans('Terminal.created')); 
    }

    public function show($id)
    {
        //
    }

    public function edit(Terminals $terminal)
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $countries = Country::orderBy('name')->get();
        $ports = Ports::get();
        return view('master.terminals.edit',[
            'countries'=>$countries,
            'terminal'=>$terminal,
            'ports'=>$ports,

        ]); 
    }

    public function update(Request $request, Terminals $terminal)
    {
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
        ]);

        $user = Auth::user();

    $CodeDublicate  = Terminals::where('id','!=',$terminal->id)->where('code',$request->code)->count();
        if($CodeDublicate > 0){
            return back()->with('alert','This Terminal Code Already Exists');
        }

    $NameDublicate  = Terminals::where('id','!=',$terminal->id)->where('name',$request->name)->count();
        if($NameDublicate > 0){
            return back()->with('alert','This Terminal Name Already Exists');
        }
        $this->authorize(__FUNCTION__,Terminals::class);
        $terminal->update($request->except('_token'));
        return redirect()->route('terminals.index')->with('success',trans('Terminal.updated.success')); 
    }

    public function destroy($id)
    {
        $terminal =Terminals::Find($id);
        $terminal->delete();
        return redirect()->route('terminals.index')->with('success',trans('Terminal.deleted.success')); 
    }
}
