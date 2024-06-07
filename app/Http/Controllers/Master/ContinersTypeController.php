<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ContainersTypes;
use Illuminate\Support\Facades\Auth;

class ContinersTypeController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,ContainersTypes::class);
        $container_types = ContainersTypes::orderBy('id')->paginate(10);
        return view('master.container-types.index',[
            'items'=>$container_types,
        ]); 

    }

    public function create()
    {
        $this->authorize(__FUNCTION__,ContainersTypes::class);
        return view('master.container-types.create');  
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);
        ContainersTypes::create([      
        'name'=> $request->input('name'),
        'code'=> $request->input('code'),
        'width'=> $request->input('width'),
        'heights'=> $request->input('heights'),
        'lenght'=> $request->input('lenght'),
        'iso_no'=> $request->input('iso_no'),
        'company_id'=>$user->company_id,
        ]);
        return redirect()->route('container-types.index')->with('success',trans('Container Types.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(ContainersTypes $container_type)
    {
        $this->authorize(__FUNCTION__,ContainersTypes::class);
        return view('master.container-types.edit',[
            'container_type'=>$container_type,
        ]); 
    }

    public function update(Request $request, ContainersTypes $container_type)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);
        $this->authorize(__FUNCTION__,ContainersTypes::class);
        $container_type->update($request->except('_token'));
        return redirect()->route('container-types.index')->with('success',trans('Container Type.updated.success'));    
    }

    public function destroy($id)
    {
        $container_type =ContainersTypes::Find($id);
        $container_type->delete();

        return redirect()->route('container-types.index')->with('success',trans('Container Type.deleted.success'));
    }
}
