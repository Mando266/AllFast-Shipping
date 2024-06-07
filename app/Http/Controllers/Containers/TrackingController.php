<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Filters\Movements\ContainersIndexFilter;
use App\Models\Master\ContainersMovement;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use DB;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $containers = Containers::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $movements = Movements::filter(new ContainersIndexFilter(request()))
        ->join('containers', 'movements.container_id', '=', 'containers.id')->where('movements.company_id',Auth::user()->company_id)
            ->select('movements.*', 'containers.code')->orderBy('movement_date','asc')->orderBy('movement_id','asc')->orderBy('id','asc')
        ->get()->groupBy('code');
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $voyages = Voyages::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $movementsBlNo = Movements::where('company_id',Auth::user()->company_id)->select('bl_no')->distinct()->get()->pluck('bl_no');

        return view('containers.tracking.index',[
            'items'=>$movements,
            'containers'=>$containers,
            'ports'=>$ports,
            'voyages'=>$voyages,
            'containersMovements'=>$containersMovements,
            'movementsBlNo'=>$movementsBlNo,

        ]);
    }

    public function create()
    {
        $containers = Containers::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $voyages = Voyages::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $movementsBlNo = Movements::where('company_id',Auth::user()->company_id)->select('bl_no')->distinct()->get()->pluck('bl_no');

        return view('containers.tracking.create',[
            'containers'=>$containers,
            'ports'=>$ports,
            'voyages'=>$voyages,
            'containersMovements'=>$containersMovements,
            'movementsBlNo'=>$movementsBlNo,

        ]);
    }

    public function store()
    {

    }

}
