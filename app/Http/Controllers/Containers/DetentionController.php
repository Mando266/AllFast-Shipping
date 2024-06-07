<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use App\Models\Containers\Period;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetentionController extends Controller
{
    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function showDetentionView()
    {
        $demurrages = Demurrage::where('company_id',Auth::user()->company_id)->where('is_storge','Detention')->get();
        $movementsBlNo = Movements::where('company_id',Auth::user()->company_id)->select('bl_no')->distinct()->get()->pluck('bl_no');
        $movements = [];
        return view('containers.detention.detentionView',[
            'items'=>$demurrages,
            'movementsBlNo'=>$movementsBlNo,
            'movements' =>$movements,
            'periods'=>[],
        ]);
    }
    
    public function calculateDetention(Request $request)
    {
        $period = Period::where('demurrage_id',$request->Triff_id)->select('rate','number_off_dayes','period')->get();
        $movementsBlNo = Movements::where('company_id',Auth::user()->company_id)->select('bl_no')->distinct()->get()->pluck('bl_no');
        // dd($movementsBlNo);
        $demurrage = Demurrage::where('id',$request->Triff_id)->get();
        $demurrages = Demurrage::where('company_id',Auth::user()->company_id)->get();
        $movements = Movements::where('company_id',Auth::user()->company_id)->where('bl_no',$request->bl_no)->with('movementcode','container')->get();
        $period = Period::where('demurrage_id',$request->Triff_id)->select('rate','number_off_dayes','period')->get();
        $periodtimeTotal = 0;
        foreach($period as $per){
            if($per->period != "Thereafter")
            $periodtimeTotal += $per->number_off_dayes;
        }

        return view('containers.detention.detentionView',[
            'items'=>$demurrages,
            'movementsBlNo'=>$movementsBlNo,
            'movements'=> $movements,
            'periods'=>$period,
            'demurrage' => $demurrage,
            'periodtimeTotal'=>$periodtimeTotal,
        ]);
    }

    public function showTriffSelectWithBlno($id,$detention,$dchfDate,$rcvcDate = null)
    {           
        $movement = Movements::find($id);
        $containerType  = ContainersTypes::where('id',$movement->container_type_id)->pluck('name')->first();
        $demurrages = Demurrage::where('company_id',Auth::user()->company_id)->get();
        
        $container_no = Containers::where('id',$movement->container_id)->pluck('code')->first();

        return view('containers.detention.index',[
            'containerType'=>$containerType,
            'container_no'=>$container_no,
            'dchfDate'=>$dchfDate,
            'rcvcDate'=>$rcvcDate,
            'periods'=>[],
            'movement'=>$movement,
            'items'=>$demurrages,
            'detention' => $detention
        ]);
    }

    
    public function showDetention(Request $request)
    {
        $movement = Movements::where('id',$request->movement_id)->with('movementcode')->first();
        $container_no = Containers::where('id',$movement->container_id)->pluck('code')->first();
        $detention = $request->detention;
        $period = Period::where('demurrage_id',$request->Triff_id)->select('rate','number_off_dayes','period')->get();
        $demurrage = Demurrage::where('id',$request->Triff_id)->get();
        $demurrages = Demurrage::where('company_id',Auth::user()->company_id)->get();
        $containerType  = ContainersTypes::where('id',$movement->container_type_id)->pluck('name')->first();
        $dchfDate = $request->dchfDate;
        $rcvcDate = $request->rcvcDate;
        $periodtimeTotal = 0;
        foreach($period as $per){
            if($per->period != "Thereafter")
            $periodtimeTotal += $per->number_off_dayes;
        }
        return view('containers.detention.index',[
            'containerType'=>$containerType,
            'container_no'=>$container_no,
            'periodtimeTotal'=>$periodtimeTotal,
            'dchfDate'=>$dchfDate,
            'rcvcDate'=>$rcvcDate,
            'movement'=>$movement,
            'periods'=>$period,
            'detention' => $detention,
            'demurrage' => $demurrage,
            'items'=>$demurrages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
