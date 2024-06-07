<?php

namespace App\Http\Controllers\Voyages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Voyages\Voyages;
use App\Models\Master\Vessels;
use App\Models\Master\Lines;
use App\Models\Master\Terminals;
use App\Models\Master\Ports;
use App\Models\Voyages\Legs;
use App\Models\Voyages\VoyagePorts;
use App\Filters\Voyages\VoyagesIndexFilter;
use Illuminate\Support\Facades\Http;
use DB;
use Illuminate\Support\Facades\Auth;

class VoyagesController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $user = Auth::user();
        $where = [];
        $Port =  request()->input('Port');
        if ($Port) $where[] = ['port_from_name', $Port];
        $FromPort =  request()->input('From');
        // if ($FromPort) $where[] = ['port_from_name','>=', $FromPort];
        $ToPort = request()->input('To');
        // if ($ToPort) $where[] = ['port_from_name','<=', $ToPort];

        $voyages = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
            ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no','voyage_port.eta')
            ->with(array('voyagePorts' => function($q) {
                return $q->orderBy('eta', 'ASC');
            }))
            ->filter(new VoyagesIndexFilter(request()))->where('company_id',$user->company_id)
            ->orderBy('eta', 'DESC')
            ->groupBy('id')
            ->with('bldrafts')
            ->paginate(30);
            // dd($voyages);
        $voyagesExport = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
        ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no','voyage_port.eta')
        ->with(array('voyagePorts' => function($q) {
            return $q->orderBy('eta', 'ASC');
        }))
        ->filter(new VoyagesIndexFilter(request()))->where('company_id',$user->company_id)
        ->orderBy('eta', 'DESC')
            ->groupBy('id')
            ->get();

            // SEARCH FROM PART
            if(isset($FromPort) && !isset($ToPort)){
                $voyages = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
                ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no','voyage_port.eta')
                    ->with(array('voyagePorts' => function($q) {
                        return $q->orderBy('eta', 'ASC');
                    }))
                    ->filter(new VoyagesIndexFilter(request()))->where('company_id',$user->company_id)
                    ->orderBy('eta', 'DESC')
                    ->where('port_from_name','>=', $FromPort)
                    ->groupBy('id')
                    ->with('bldrafts')
                    ->paginate(30);
                $voyagesExport = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
                ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no','voyage_port.eta')
                ->with(array('voyagePorts' => function($q) {
                    return $q->orderBy('eta', 'ASC');
                }))
                ->filter(new VoyagesIndexFilter(request()))->where('company_id',$user->company_id)
                ->orderBy('eta', 'DESC')
                    ->where('port_from_name','>=', $FromPort)
                    ->groupBy('id')
                    ->get();

                foreach ($voyages as $key => $voyage) {
                    $tempPortsFrom = $voyage->voyagePorts->where('port_from_name', $FromPort)->pluck('id')->toArray();
                    if(!isset($tempPortsFrom[0])){
                        unset($voyages[$key]);
                    }else{
                        foreach($voyage->voyagePorts as $key => $voyagePort){
                            if(!in_array($voyagePort->id,$tempPortsFrom)){
                                unset($voyage->voyagePorts[$key]);
                            }
                        }
                    }
                }
            }//check for filter from : to
            elseif(isset($FromPort) && isset($ToPort)){
                $voyages = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
                ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no','voyage_port.eta')
                    ->with(array('voyagePorts' => function($q) {
                        return $q->orderBy('eta', 'ASC');
                    }))
                    ->filter(new VoyagesIndexFilter(request()))
                    ->orderBy('eta', 'DESC')
                    ->where('company_id',$user->company_id)
                    ->where(function ($query) use ($FromPort,$ToPort) {
                        $query->where('port_from_name','>=', $FromPort)
                        ->orWhere('port_from_name','<=', $ToPort);
                    })
                    ->groupBy('id')
                    ->with('bldrafts')
                    ->paginate(30);
                $voyagesExport = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
                ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no','voyage_port.eta')
                ->with(array('voyagePorts' => function($q) {
                    return $q->orderBy('eta', 'ASC');
                }))
                ->filter(new VoyagesIndexFilter(request()))
                ->orderBy('eta', 'DESC')
                    ->where('company_id',$user->company_id)
                    ->where(function ($query) use ($FromPort,$ToPort) {
                        $query->where('port_from_name','>=', $FromPort)
                        ->orWhere('port_from_name','<=', $ToPort);
                    })
                    ->groupBy('id')
                    ->get();

                foreach ($voyages as $key => $voyage) {
                    $tempPortsFrom = $voyage->voyagePorts->where('port_from_name', $FromPort)->pluck('id')->toArray();
                    $tempPortsTo = $voyage->voyagePorts->where('port_from_name', $ToPort)->pluck('id')->toArray();
                    if(!isset($tempPortsFrom[0]) || !isset($tempPortsTo[0])){
                        unset($voyages[$key]);
                }
                    $voyageports = VoyagePorts::where('voyage_id',$voyage->id)
                    ->where(function ($query) use ($FromPort,$ToPort) {
                        $query->where('port_from_name', $FromPort)
                              ->orWhere('port_from_name', $ToPort);
                    })->get();
                    if($voyageports->count() == 0){
                        unset($voyages[$key]);
                    }elseif($voyageports[0]->port_from_name != $FromPort){
                        unset($voyages[$key]);

                    }else{
                        $voyageports = $voyageports->pluck('id')->toArray();
                        foreach($voyage->voyagePorts as $key => $voyagePort){
                            if(!in_array($voyagePort->id,$voyageports)){
                                unset($voyage->voyagePorts[$key]);
                            }
                        }
                    }
            }
        }
        $vessels = Vessels::where('company_id',$user->company_id)->orderBy('name')->get();
        $ports = Ports::where('company_id',$user->company_id)->orderBy('name')->get();
        session()->flash('voyages',$voyagesExport);
        return view('voyages.voyages.index',[
            'items'=>$voyages,
            'vessels'=>$vessels,
            'ports'=>$ports,
            'FromPort'=> $FromPort,
            'ToPort'=> $ToPort
        ]);
     }

    public function create()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $user = Auth::user();
        $vessels = Vessels::where('company_id',$user->company_id)->orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        $lines = Lines::where('company_id',$user->company_id)->orderBy('id')->get();
        $terminals = [];
        $ports = Ports::where('company_id',$user->company_id)->orderBy('id')->get();
        return view('voyages.voyages.create',[
            'vessels'=>$vessels,
            'legs'=>$legs,
            'lines'=>$lines,
            'terminals'=>$terminals,
            'ports'=>$ports,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'voyage_no' => 'required',
            'vessel_id' => 'required',
        ]);
        foreach($request->voyageport as $voyagePort){
            if($voyagePort['etd'] < $voyagePort['eta']){
                return back()->with('error','Voyage ETD Must Be Bigger Than or Equal ETA');
            }
        }
        $uniqueVoyagePort = [];
        foreach ($request->voyageport as $voyageport) {
            $portName = $voyageport['port_from_name'];
            if ($portName !== null && !in_array($portName, $uniqueVoyagePort, true)) {
                array_push($uniqueVoyagePort, $portName);
            } elseif ($portName !== null) {
                return redirect()->back()->with('error', 'Voyage Port Must be unique')->withInput($request->input());
            }
        }
        $VoyagesDublicate  = Voyages::where('company_id',$user->company_id)->where('vessel_id',$request->vessel_id)->where('voyage_no',$request->voyage_no)->where('leg_id',$request->leg_id)->first();
            if($VoyagesDublicate != null && $VoyagesDublicate->vessel_id != null && $VoyagesDublicate->voyage_no != null && $VoyagesDublicate->leg_id != null ){
                return back()->with('error','This Voyage Already Exists');
        }

        $voyages = Voyages::create([
            'vessel_id'=> $request->input('vessel_id'),
            'voyage_no'=> $request->input('voyage_no'),
            'leg_id'=> $request->input('leg_id'),
            'line_id'=> $request->input('line_id'),
            'notes'=> $request->input('notes'),
            'principal_name'=> $request->input('principal_name'),
            'exchange_rate'=> $request->input('exchange_rate'),
            'job_no'=> $request->input('job_no'),
            'company_id'=>$user->company_id,
        ]);

        foreach($request->input('voyageport',[]) as $voyageport){
            VoyagePorts::create([
                'voyage_id'=>$voyages->id,
                'port_from_name'=>$voyageport['port_from_name'],
                'terminal_name'=>$voyageport['terminal_name'],
                'road_no'=>$voyageport['road_no'],
                'eta'=>$voyageport['eta'],
                'etd'=>$voyageport['etd'],

            ]);
        }
        return redirect()->route('voyages.index')->with('success',trans('voyage.created'));
    }

    public function show($id,$from = null,$to = null)
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $voyage = Voyages::find($id);
        if($from == null && $to == null){
            $voyageports = VoyagePorts::where('voyage_id',$id)->get();
        // }elseif($from == null){
        //     $voyageports = VoyagePorts::where('voyage_id',$id)
        // ->where('port_from_name', $to)->get();
        }elseif($to == null){
            // Here is trips when we search with from just
            $tempPortsFrom = VoyagePorts::where('voyage_id',$id)->where('port_from_name', $from)->get();
            if(!isset($tempPortsFrom[0])){
                $voyageports = [];
            }else{
                $voyageports = VoyagePorts::where('voyage_id',$id)
                        ->where('port_from_name', $from)->get();
            }

        }else{
            // Here is trips when we search with from : to
                // here we check if we found ports for this voyage from and to our search
                $tempPortsFrom = VoyagePorts::where('voyage_id',$id)->where('port_from_name', $from)->get();
                $tempPortsTo = VoyagePorts::where('voyage_id',$id)->where('port_from_name', $to)->get();

                // dd(!isset($tempPortsFrom[0]) || !isset($tempPortsTo[0]));

                if(!isset($tempPortsFrom[0]) || !isset($tempPortsTo[0])){
                    $voyageports = [];
                }else{
                    $voyageports = VoyagePorts::where('voyage_id',$id)
                    ->where(function ($query) use ($from,$to) {
                        $query->where('port_from_name', $from)
                              ->orWhere('port_from_name', $to);
                    })->get();
                }


        }

        $voyageport = Voyages::where('id',$id)->first();

        return view('voyages.voyages.show',[
            'voyage'=>$voyage,
            'voyageports'=>$voyageports,
            'voyageport'=>$voyageport,
        ]);
    }

    public function edit(Voyages $voyage)
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $voyage_ports = VoyagePorts::where('voyage_id',$voyage->id)->with('voyage')->get();
        //dd($voyage_ports);
        $vessels = Vessels::where('company_id',Auth::user()->company_id)->orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        $lines = Lines::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $terminals = Terminals::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();

        return view('voyages.voyages.edit',[
            'voyage_ports'=>$voyage_ports,
            'voyage'=>$voyage,
            'vessels'=>$vessels,
            'legs'=>$legs,
            'lines'=>$lines,
            'terminals'=>$terminals,
            'ports'=>$ports,
        ]);
    }

    public function update(Request $request, Voyages $voyage)
    {
        // $request->validate([
        //     'eta' => 'required',
        //     'etd' => ['required','after_or_equal:eta'],
        // ],[
        //     'etd.after_or_equal'=>'ETD Should Be After Or Equal ETA',
        // ]);
        //dd($request->input());
        $user = Auth::user();
        $VoyageDublicate  = Voyages::where('id','!=',$voyage->id)->where('company_id',$user->company_id)->where('vessel_id',$request->vessel_id)->where('voyage_no',$request->voyage_no)->where('leg_id',$request->leg_id)->first();
        $uniqueVoyagePort = [];

        foreach($request->voyageport as $voyagePort){
            if($voyagePort['etd'] < $voyagePort['eta']){
                return back()->with('error','Voyage ETD Must Be Bigger Than or Equal ETA');
            }

            if ($voyagePort['port_from_name'] !== null && !in_array($voyagePort['port_from_name'], $uniqueVoyagePort, true)) {
                array_push($uniqueVoyagePort, $voyagePort['port_from_name']);
            } elseif ($voyagePort['port_from_name'] !== null) {
                return redirect()->back()->with('error', 'Voyage Port Must be unique')->withInput($request->input());
            }
        }
        if($VoyageDublicate != null){
            if($VoyageDublicate->count() > 0){
                    if($VoyageDublicate->vessel_id != null && $VoyageDublicate->voyage_no != null && $VoyageDublicate->leg_id != null){
                        return back()->with('error','This Voyage Already Exists');
                    }
                }
        }
        $this->authorize(__FUNCTION__,VoyagePorts::class);
        $inputs = request()->all();
        unset($inputs['voyageport'],$inputs['_token'],$inputs['removed']);
        $voyage->update($inputs);
        VoyagePorts::destroy(explode(',',$request->removed));
        $voyage->createOrUpdatevoyageport($request->voyageport);
        return redirect()->route('voyages.index')->with('success',trans('voyage.updated.success'));
    }

    public function destroy($id)
    {
        $voyage = Voyages::find($id);
        VoyagePorts::where('voyage_id',$id)->delete();
        $voyage->delete();
        return redirect()->route('voyages.index')->with('success',trans('voyage.deleted.success'));
    }
}
