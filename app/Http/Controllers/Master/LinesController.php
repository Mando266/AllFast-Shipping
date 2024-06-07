<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use Illuminate\Http\Request;
use App\Models\Master\Lines;
use App\Models\Master\LinesType;
use App\Models\Master\LinesWithType;
use Illuminate\Support\Facades\Auth;
class LinesController extends Controller
{
  
    public function index()
    {
        $this->authorize(__FUNCTION__,Lines::class);

            $lines = Lines::where('company_id',Auth::user()->company_id)->with('types.type')->orderBy('id')->paginate(10);
        
        return view('master.lines.index',[
            'items'=>$lines,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $line_types = LinesType::where('company_id',Auth::user()->company_id)->orderBy('name')->get();
        return view('master.lines.create',[
            'line_types'=>$line_types,
            'countries' => Country::orderBy('name')->get(),
        ]); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $request->validate([ 
            'code' => 'required', 
            'name' => 'required', 
            'country_id' => 'required'
        ]);
        $user = Auth::user();

        $CodeDublicate  = Lines::where('company_id',$user->company_id)->where('code',$request->code)->first();
        if($CodeDublicate != null){
            return back()->with('alert','This Line Code Already Exists');
        }
        $NameDublicate  = Lines::where('company_id',$user->company_id)->where('name',$request->name)->first();
        if($NameDublicate != null){
            return back()->with('alert','This Line Name Already Exists');
        }
        $line = Lines::create($request->except('_token', 'line_type_id', 'contactPeople'));
        $line->company_id = $user->company_id;
        $line->save();
        $line->storeContactPeople(request()->contactPeople);
        if(isset($request->line_type_id)){
            if(count($request->line_type_id) > 0){
                foreach($request->line_type_id as $lineType){
                    LinesWithType::create([
                        'line_id'=>$line->id,
                        'type_id'=>$lineType['type_id']
                    ]);
                }
            }
        }
        return redirect()->route('lines.index')->with('success',trans('line.created'));   
    }

    public function show($id)
    {
        //
    }

    public function edit(Lines $line)
    {
        $this->authorize(__FUNCTION__,Lines::class);
        $line_types = LinesType::orderBy('name')->where('company_id',Auth::user()->company_id)->get();
        $types = LinesWithType::where('line_id',$line->id)->get()->pluck('type_id')->toarray();
        return view('master.lines.edit',[
            'types'=>$types,
            'line'=>$line,
            'line_types'=>$line_types,
            'countries' => Country::orderBy('name')->get(),
            'contactPeople' => $line->contactPeople
        ]);     
    }

    public function update(Request $request,Lines $line)
    {
        $user = Auth::user();

        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'country_id' => 'required'
        ]);
        $line->storeContactPeople(request()->contactPeople);

        $CodeDublicate  = Lines::where('id','!=',$line->id)->where('company_id',$user->company_id)->where('code',$request->code)->count();
        if($CodeDublicate > 0){
            return back()->with('alert','This Line Code Already Exists');
        }
        $NameDublicate  = Lines::where('id','!=',$line->id)->where('company_id',$user->company_id)->where('name',$request->name)->count();
        if($NameDublicate > 0){
            return back()->with('alert','This Line Name Already Exists');
        }

        $this->authorize(__FUNCTION__,Lines::class);
        $line->update($request->except('_token', 'line_type_id', 'contactPeople'));
        $newTypes = [];
        foreach($request->line_type_id as $item){
            array_push($newTypes,$item['type_id']);
        }
        $Oldtypes = LinesWithType::where('line_id',$line->id)->get();
        if(isset($Oldtypes)){
            if($Oldtypes->count() > 0){
                foreach($Oldtypes as $old){
                    if(!in_array($old->type_id, $newTypes)){
                        $old->delete();
                    }else{
                        $key = array_search($old->type_id, $newTypes);
                        unset($newTypes[$key]);
                    }
                }
                if(count($newTypes) > 0){
                    foreach($newTypes as $type){
                        LinesWithType::create([
                            'type_id'=>$type,
                            'line_id'=>$line->id
                        ]);
                    }
                }
            }else{
                if(isset($request->line_type_id)){
                    if(count($newTypes) > 0){
                        foreach($newTypes as $type){
                            LinesWithType::create([
                                'type_id'=>$type,
                                'line_id'=>$line->id
                            ]);
                        }
                    }
                }
            }
        }else{
            if(isset($request->line_type_id)){
                if(count($newTypes) > 0){
                    foreach($newTypes as $type){
                        LinesWithType::create([
                            'type_id'=>$type,
                            'line_id'=>$line->id
                        ]);
                    }
                }
            }
        }
        
        return redirect()->route('lines.index')->with('success',trans('line.updated.success')); 
    }

    public function destroy($id)
    {
        $line =Lines::Find($id);
        $lineTypes = LinesWithType::where('line_id',321)->get();
        if($lineTypes->count() > 0 ){
            foreach($lineTypes as $type){
                $type->delete();
            }
        }
        $line->delete();

        return redirect()->route('lines.index')->with('success',trans('line.deleted.success'));
    }
}
