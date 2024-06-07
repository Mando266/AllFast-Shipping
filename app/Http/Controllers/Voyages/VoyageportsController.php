<?php

namespace App\Http\Controllers\Voyages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Terminals;
use App\Models\Master\Ports;
use App\Models\Voyages\VoyagePorts;
use DB;
use Illuminate\Support\Facades\Auth;
class VoyageportsController extends Controller
{


    public function create()
    {
        $user = Auth::user();
        $terminals = [];
        $ports = Ports::where('company_id',$user->company_id)->orderBy('id')->get();
        return view('voyages.voyageports.create',[
            'terminals'=>$terminals,
            'ports'=>$ports,
        ]);
    }

    public function store(Request $request)
    {

        foreach($request->input('voyageport',[]) as $voyageport){
            
            VoyagePorts::create([
                'voyage_id'=> $request->input('voyage_id'),
                'port_from_name'=>$voyageport['port_from_name'],
                'terminal_name'=>$voyageport['terminal_name'],
                'road_no'=>$voyageport['road_no'],
                'eta'=>$voyageport['eta'],
                'etd'=>$voyageport['etd'],
            ]);
        }

        return redirect()->route('voyages.index')->with('success',trans('voyage.created'));
    }

}
