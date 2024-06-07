<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice\ChargesDesc;
use Illuminate\Support\Facades\Auth;
class ChargesDescController extends Controller
{
  
    public function index()
    {
        $this->authorize(__FUNCTION__,ChargesDesc::class);

        $chargesDesc = ChargesDesc::orderBy('id')->paginate(30);
        //dd($chargesDesc);
        return view('master.chargesDesc.index',[
            'items'=>$chargesDesc,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,ChargesDesc::class);

        return view('master.chargesDesc.create'); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,ChargesDesc::class);
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
        ]);
        $user = Auth::user();

        $NameDublicate  = ChargesDesc::where('name',$request->name)->first();
        if($NameDublicate != null){
            return back()->with('alert','This Item Name Already Exists');
        }
        $chargesDesc = ChargesDesc::create($request->except('_token'));
        $chargesDesc->company_id = $user->company_id;
        $chargesDesc->save();

        return redirect()->route('chargesDesc.index')->with('success',trans('Charges Description.Created'));   
    }

    public function show($id)
    {
        //
    }

    public function edit(ChargesDesc $chargesDesc)
    {
        $this->authorize(__FUNCTION__,ChargesDesc::class);

        return view('master.chargesDesc.edit',[
            'chargesDesc'=>$chargesDesc,
        ]);      
    }

    public function update(Request $request,ChargesDesc $chargesDesc)
    {
        $user = Auth::user();

        $request->validate([
            'code' => 'required',
            'name' => 'required',
        ]);

        $NameDublicate  = ChargesDesc::where('id','!=',$chargesDesc->id)->where('company_id',$user->company_id)->where('name',$request->name)->count();
        if($NameDublicate > 0){
            return back()->with('alert','This Item Name Already Exists');
        }

        $this->authorize(__FUNCTION__,ChargesDesc::class);

        $chargesDesc->update($request->except('_token'));
        return redirect()->route('chargesDesc.index')->with('success',trans('Charges Description.Updated.Success')); 
    }

    public function destroy($id)
    {
        $chargesDesc =ChargesDesc::Find($id);
        $chargesDesc->delete();

        return redirect()->route('chargesDesc.index')->with('success',trans('Charges Description.Deleted.Success'));
    }
}
