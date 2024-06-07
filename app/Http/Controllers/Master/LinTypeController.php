<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\LinesType;
use Illuminate\Support\Facades\Auth;

class LinTypeController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,LinesType::class);
    
            $line_types = LinesType::where('company_id',Auth::user()->company_id)->orderBy('id')->paginate(10);;
        
        return view('master.line-types.index',[
            'items'=>$line_types,
        ]); 

    }

    public function create()
    {
        $this->authorize(__FUNCTION__,LinesType::class);
        return view('master.line-types.create');  

    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $NameDublicate  = LinesType::where('company_id',$user->company_id)->where('name',$request->name)->first();
        if($NameDublicate != null){
            return back()->with('alert','This Line Type Name Already Exists');
        }

        LinesType::create([      
        'name'=> $request->input('name'),
        'company_id'=>$user->company_id,
        ]);
        return redirect()->route('line-types.index')->with('success',trans('Line Type.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(LinesType $line_type)
    {
        $this->authorize(__FUNCTION__,LinesType::class);
        return view('master.line-types.edit',[
            'line_type'=>$line_type,
        ]);
    }

    public function update(Request $request,LinesType $line_type)
    {
        $user = Auth::user();

        $NameDublicate  = LinesType::where('id','!=',$line_type->id)->where('company_id',$user->company_id)->where('name',$request->name)->count();
        if($NameDublicate > 0){
            return back()->with('alert','This Line Type Name Already Exists');
        }

        $this->authorize(__FUNCTION__,LinesType::class);
        $line_type->update($request->except('_token'));
        return redirect()->route('line-types.index')->with('success',trans('Line Type.updated.success'));
    }

    public function destroy($id)
    {
        $line_type =LinesType::Find($id);
        $line_type->delete();

        return redirect()->route('line-types.index')->with('success',trans('Line Type.deleted.success'));  
    }
}
