<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\PortTypes;
use Illuminate\Support\Facades\Auth;

class PortTypesController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,PortTypes::class);

            $port_types = PortTypes::where('company_id',Auth::user()
            ->company_id)->UserPortsTypes()->orderBy('id')->paginate(10);
        
        return view('master.port-types.index',[
            'items'=>$port_types,
        ]); 
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,PortTypes::class);
        return view('master.port-types.create');  
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $NameDublicate  = PortTypes::where('company_id',$user->company_id)->where('name',$request->name)->first();

        if($NameDublicate != null){
            return back()->with('alert','This Port Type Name Already Exists');
        }

        PortTypes::create([      
        'name'=> $request->input('name'),
        'company_id'=>$user->company_id,
        ]);
        return redirect()->route('port-types.index')->with('success',trans('Port Types.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(PortTypes $port_type)
    {
        $this->authorize(__FUNCTION__,PortTypes::class);
        return view('master.port-types.edit',[
            'port_type'=>$port_type,
        ]);     
    }

    public function update(Request $request, PortTypes $port_type)
    {
        $request->validate([ 
            'name' => 'required', 
        ]);
        $user = Auth::user();
        
        $NameDublicate  = PortTypes::where('id','!=',$port_type->id)->where('company_id',$user->company_id)->where('name',$request->name)->count();

        if($NameDublicate > 0){
            return back()->with('alert','This Port Type Name Already Exists');
        }

        $this->authorize(__FUNCTION__,PortTypes::class);
        $port_type->update($request->except('_token'));
        return redirect()->route('port-types.index')->with('success',trans('Port Type.updated.success'));    
    }

    public function destroy($id)
    {
        $port_type =PortTypes::Find($id);
        $port_type->delete();

        return redirect()->route('port-types.index')->with('success',trans('Port Type.deleted.success'));
    }
}
