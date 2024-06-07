<?php

namespace App\Http\Controllers\Master;

use App\Filters\User\UserIndexFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Country;
use App\Models\Master\PortTypes;
use App\Models\Master\Ports;
use App\Models\Master\Terminals;
use App\Models\Master\Agents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PortsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Ports::class);

            $ports = Ports::filter(new UserIndexFilter(request()))->orderBy('id')->orderBy('id')->paginate(30);
            $port = Ports::get();
        
        return view('master.ports.index',[
            'items'=>$ports,
            'port'=>$port,

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $user = Auth::user();
        $countries = Country::orderBy('name')->get();
        $port_types = PortTypes::orderBy('name')->get();
        $terminals = Terminals::orderBy('name')->get();
        return view('master.ports.create',[
            'countries'=>$countries,
            'port_types'=>$port_types,
            'terminals'=>$terminals,
        ]); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
        ]);        
        $user = Auth::user();

        $CodeDublicate  = Ports::where('code',$request->code)->first();
        if($CodeDublicate != null){
            return back()->with('alert','This Port Code Already Exists');
        }

        $NameDublicate  = Ports::where('name',$request->name)->first();
        if($NameDublicate != null){
            return back()->with('alert','This Port Name Already Exists');
        }

        $ports = Ports::create($request->except('_token'));
        $ports->agent_id = $user->agent_id;
        $ports->save();
        
        return redirect()->route('ports.index')->with('success',trans('port.created')); 
    }

    public function show(Ports $port)
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $countries = Country::orderBy('name')->get();
        $port_types = PortTypes::orderBy('name')->get();
        $terminals = Terminals::orderBy('name')->get();
        return view('master.ports.show',[
            'port'=>$port,
            'countries'=>$countries,
            'port_types'=>$port_types,
            'terminals'=>$terminals,
        ]);
    }

    public function edit(Ports $port)
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $user = Auth::user();
        $countries = Country::orderBy('name')->get();
        $port_types = PortTypes::orderBy('name')->get();
        $terminals = Terminals::orderBy('name')->get();
        return view('master.ports.edit',[
            'port'=>$port,
            'countries'=>$countries,
            'port_types'=>$port_types,
            'terminals'=>$terminals,
        ]); 
    }

    public function update(Request $request, Ports $port)
    {
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
        ]);
        $user = Auth::user();
        
        $CodeDublicate  = Ports::where('id','!=',$port->id)->where('code',$request->code)->count();
        if($CodeDublicate > 0){
            return back()->with('alert','This Port Code Already Exists');
        }

        $NameDublicate  = Ports::where('id','!=',$port->id)->where('name',$request->name)->count();
        if($NameDublicate > 0){
            return back()->with('alert','This Port Name Already Exists');
        }
        $this->authorize(__FUNCTION__,Ports::class);
        $port->update($request->except('_token'));
        return redirect()->route('ports.index')->with('success',trans('Port.updated.success')); 
    }

    public function destroy($id)
    {
        $port =Ports::Find($id);
        $port->delete();
        return redirect()->route('ports.index')->with('success',trans('Port.deleted.success'));
    }
}
