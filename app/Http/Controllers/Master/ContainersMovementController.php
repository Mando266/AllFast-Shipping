<?php

namespace App\Http\Controllers\Master;

use App\Filters\Containers\ContainersIndexFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ContainersMovement;
use App\Models\Master\StockTypes;
use App\Models\Master\ContainerStatus;
use Illuminate\Support\Facades\Auth;

class ContainersMovementController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,ContainersMovement::class);
        $containers_movement = ContainersMovement::filter(new ContainersIndexFilter(request()))->orderBy('id')->paginate(10);
        $movementscode = ContainersMovement::orderBy('id')->get();

        return view('master.container-movement.index',[
            'items'=>$containers_movement,
            'movementscode'=>$movementscode,

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,ContainersMovement::class); 
        $container_stock = StockTypes::orderBy('id')->get();
        $container_status = ContainerStatus::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();

        return view('master.container-movement.create',[
            'container_stock'=>$container_stock,
            'container_status'=>$container_status,
            'containersMovements'=>$containersMovements,

        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|unique:containers_movement|max:255',
            'code' => 'required|unique:containers_movement|max:255',
            'stock_type_id' => 'integer|nullable',
            'container_statuss_id' => 'integer|nullable',

        ],[
            'name.unique'=>'This Movement Name Already Exists ',
            'code.unique'=>'This Movement Code Already Exists ',

        ]);
        $next_move = "";
        if($request->movement != null){
            foreach($request->movement as $move){
                $next_move .= $move['code'] . ', ';
            }
        }
        ContainersMovement::create([
            'next_move'=>$next_move,
            'name'=> $request->input('name'),
            'code'=> $request->input('code'),
            'stock_type_id'=> $request->input('stock_type_id'),
            'container_status_id'=> $request->input('container_status_id'),
            'company_id'=> $request->input('company_id'),
            ]);
        return redirect()->route('container-movement.index')->with('success',trans('Container Movement.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(ContainersMovement $container_movement)
    {
        $this->authorize(__FUNCTION__,ContainersMovement::class);
        $container_stock = StockTypes::orderBy('id')->get();
        $container_status = ContainerStatus::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        
        $next_move = explode(", ", $container_movement->next_move);
        
        return view('master.container-movement.edit',[
            'next_move' => $next_move,
            'container_movement'=>$container_movement,
            'container_stock'=>$container_stock,
            'container_status'=>$container_status,
            'containersMovements'=>$containersMovements,

        ]);
    }

    public function update(Request $request, ContainersMovement $container_movement)
    {
        $this->authorize(__FUNCTION__,ContainersMovement::class);
        $next_move = "";
        if($request->next_move != null){
            foreach($request->next_move as $move){
                $next_move .= $move['code'] . ', ';
            }
        }
        $container_movement->update($request->except('_token','next_move'));
        $container_movement->next_move = $next_move;
        $container_movement->save();

        return redirect()->route('container-movement.index')->with('success',trans('Container Movement.updated.success'));
    }

    public function destroy($id)
    {
        $container_movement =ContainersMovement::Find($id);
        $container_movement->delete();

        return redirect()->route('container-movement.index')->with('success',trans('Container Movement.deleted.success'));
    }
}
