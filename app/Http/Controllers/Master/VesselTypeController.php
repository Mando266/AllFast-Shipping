<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\VesselType;
use Illuminate\Support\Facades\Auth;

class VesselTypeController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,VesselType::class);
        $vessel_types = VesselType::orderBy('id')->paginate(10);

        return view('master.vessel-types.index',[
            'items'=>$vessel_types,
        ]); 
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,VesselType::class);
        return view('master.vessel-types.create');      }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
        ]);
        VesselType::create([      
        'name'=> $request->input('name'),
        'company_id'=>$user->company_id,
        ]);
        return redirect()->route('vessel-types.index')->with('success',trans('vessel Types.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(VesselType $vessel_type)
    {
        $this->authorize(__FUNCTION__,VesselType::class);
        return view('master.vessel-types.edit',[
            'vessel_type'=>$vessel_type,
        ]);   
    }

    public function update(Request $request, VesselType $vessel_type)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $this->authorize(__FUNCTION__,VesselType::class);
        $vessel_type->update($request->except('_token'));
        return redirect()->route('vessel-types.index')->with('success',trans('vessel Type.updated.success'));
    }


    public function destroy($id)
    {
        $vessel_type =VesselType::Find($id);
        $vessel_type->delete();

        return redirect()->route('vessel-types.index')->with('success',trans('vessel Type.deleted.success'));
    }
}
