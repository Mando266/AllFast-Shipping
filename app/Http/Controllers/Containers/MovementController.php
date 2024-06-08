<?php

namespace App\Http\Controllers\Containers;

use App\Filters\Movements\ContainersIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use App\Models\Containers\Period;
use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainerStatus;
use App\Models\Master\ContainersTypes;
use App\Models\Master\ContinerOwnership;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\Voyages\Voyages;
use App\MovementImportErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__, Movements::class);
    
        // Fetch common data used in different conditions
        $company_id = Auth::user()->company_id;
        $movementErrors = MovementImportErrors::all();
        $container_id = request()->input('container_id');
        $plNo = request()->input('bl_no');
        $movementIdFilter = request('movement_id');
        $portLocationIdFilter = request('port_location_id');
        $tillDate = request('TillDate');
    
        // Fetch bookings and unique BL numbers
        $bookings = Booking::where('company_id', $company_id)->orderBy('id')->get();
        $movementsBlNo = Movements::where('company_id', $company_id)->select('bl_no')->distinct()->pluck('bl_no');
    
        // Fetch and group movements by container_id
        $filteredData = Movements::filter(new ContainersIndexFilter(request()))
            ->orderBy('id')
            ->groupBy('container_id')
            ->with('container')
            ->get();
    
        $myMoves = Movements::orderBy('movement_date', 'desc')
            ->with('movementcode.containerstock')
            ->get()
            ->groupBy('container_id');
    
        // Remove elements if last movement doesn't include movement_id or port_location_id
        if ($movementIdFilter || $portLocationIdFilter) {
            $filteredData = $this->applyMovementAndLocationFilters($filteredData, $myMoves, $movementIdFilter, $portLocationIdFilter);
        }
    
        // Handle TillDate and Triff_id if provided
        $lastDCHF = null;
        $periods = null;
        if ($tillDate) {
            $periods = Period::where('demurrage_id', request('Triff_id'))->get();
            $movements = Movements::where('container_id', $container_id)
                ->orderBy('movement_date', 'desc')
                ->with('movementcode.containerstock')
                ->get();
    
            $movements = $this->sortAndCollapseMovements($movements);
            $lastDCHF = $movements->where('movement_id', ContainersMovement::where('code', 'DCHF')->pluck('id')->first())->first();
        }
    
        // Prepare data for final query
        $temp = $filteredData->pluck('id')->toArray();
    
        if ($filteredData->count() == 1) {
            $filteredData = $filteredData->first();
        }
    
        $movements = Movements::whereIn('id', $temp)
            ->where('company_id', $company_id)
            ->orderBy('movement_date', 'desc')
            ->orderBy('id', 'desc')
            ->groupBy('container_id')
            ->with('container.containersOwner')
            ->paginate(30);
    
        foreach ($movements as $move) {
            $tempMovements = $myMoves[$move->container_id] ?? Movements::where('container_id', $move->container_id)
                ->orderBy('movement_date', 'desc')
                ->with('movementcode.containerstock')
                ->get();
    
            $tempMovements = $this->sortAndCollapseMovements($tempMovements);
    
            // Apply additional filters
            if ($plNo) {
                $tempMovements = $tempMovements->where('bl_no', $plNo);
            }
            if ($voyageFilter = request('voyage_id')) {
                $tempMovements = $tempMovements->where('voyage_id', $voyageFilter);
            }
            if ($bookingNoFilter = request('booking_no')) {
                $tempMovements = $tempMovements->filter(function ($item) use ($bookingNoFilter) {
                    return preg_match("/$bookingNoFilter/", $item['booking_no']);
                });
            }
    
            $lastMove = $tempMovements->first();
    
            if ($lastMove) {
                $this->copyMovementDetails($move, $lastMove);
            }
        }
    
        // Prepare export movements
        $exportMovements = Movements::where('company_id', $company_id)
            ->whereIn('id', $temp)
            ->orderBy('movement_date', 'desc')
            ->orderBy('id', 'desc')
            ->groupBy('container_id')
            ->with('container.containersOwner', 'movementcode.containerstock')
            ->get();
    
        // Fetch additional data for the view
        $containers = Containers::where('company_id', $company_id)->orderBy('id')->get();
        $lessor = Containers::where('company_id', $company_id)
            ->where('description', '!=', null)
            ->orderBy('id')
            ->select('description')
            ->distinct()
            ->get();
        $ports = Ports::where('company_id', $company_id)->orderBy('id')->get();
        $voyages = Voyages::where('company_id', $company_id)->orderBy('id')->get();
        $container_ownership = ContinerOwnership::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $containerstatus = ContainerStatus::orderBy('id')->get();
        $vessels = Vessels::where('company_id', $company_id)->orderBy('name')->get();
    
        session()->flash('items', $exportMovements);
    
        return view('containers.movements.index', [
            'items' => $movements,
            'containerstatus' => $containerstatus,
            'movementsBlNo' => $movementsBlNo,
            'bookings' => $bookings,
            'containers' => $containers,
            'movementerrors' => $movementErrors,
            'plNo' => $plNo,
            'lessor' => $lessor,
            'ports' => $ports,
            'container_ownership' => $container_ownership,
            'voyages' => $voyages,
            'containersMovements' => $containersMovements,
            'vessels' => $vessels,
        ]);
    }
    
    /**
     * Apply filters for movement_id and port_location_id to the filtered data.
     *
     * @param \Illuminate\Support\Collection $filteredData
     * @param \Illuminate\Support\Collection $myMoves
     * @param mixed $movementIdFilter
     * @param mixed $portLocationIdFilter
     * @return \Illuminate\Support\Collection
     */
    private function applyMovementAndLocationFilters($filteredData, $myMoves, $movementIdFilter, $portLocationIdFilter)
    {
        $filteredData = $this->getLastMoveForEachContainer($filteredData);
    
        if ($movementIdFilter) {
            $filteredData = $filteredData->filter(function ($move) use ($myMoves, $movementIdFilter) {
                $tempMovements = $myMoves[$move->container_id] ?? Movements::where('container_id', $move->container_id)
                    ->orderBy('movement_date', 'desc')
                    ->with('movementcode.containerstock')
                    ->get();
    
                $tempMovements = $this->sortAndCollapseMovements($tempMovements);
                $lastMove = $tempMovements->first();
    
                return $lastMove && in_array($lastMove->movement_id, (array)$movementIdFilter);
            });
        }
    
        if ($portLocationIdFilter) {
            $filteredData = $filteredData->filter(function ($move) use ($myMoves, $portLocationIdFilter) {
                $tempMovements = $myMoves[$move->container_id] ?? Movements::where('container_id', $move->container_id)
                    ->orderBy('movement_date', 'desc')
                    ->with('movementcode.containerstock')
                    ->get();
    
                $tempMovements = $this->sortAndCollapseMovements($tempMovements);
                $lastMove = $tempMovements->first();
    
                return $lastMove && in_array($lastMove->port_location_id, (array)$portLocationIdFilter);
            });
        }
    
        return $filteredData;
    }
    
    /**
     * Get the last movement for each container from the given collection.
     *
     * @param \Illuminate\Support\Collection $movements
     * @return \Illuminate\Support\Collection
     */
    private function getLastMoveForEachContainer($movements)
    {
        return $movements->groupBy('container_id')->map(function ($group) {
            return $group->sortByDesc('movement_date')->first();
        });
    }
    
    /**
     * Sort and collapse movements by movement_date and sequence.
     *
     * @param \Illuminate\Support\Collection $movements
     * @return \Illuminate\Support\Collection
     */
    private function sortAndCollapseMovements($movements)
    {
        return $movements->groupBy('movement_date')
            ->map(function ($group) {
                return $group->sortByDesc('movementcode.sequence');
            })
            ->collapse();
    }
    
    /**
     * Copy details from one movement to another.
     *
     * @param \App\Models\Movements $move
     * @param \App\Models\Movements $lastMove
     */
    private function copyMovementDetails(&$move, $lastMove)
    {
        $move->bl_no = $lastMove->bl_no;
        $move->port_location_id = $lastMove->port_location_id;
        $move->movement_date = $lastMove->movement_date;
        $move->movement_id = $lastMove->movement_id;
        $move->container_type_id = $lastMove->container_type_id;
        $move->pol_id = $lastMove->pol_id;
        $move->pod_id = $lastMove->pod_id;
        $move->vessel_id = $lastMove->vessel_id;
        $move->voyage_id = $lastMove->voyage_id;
        $move->terminal_id = $lastMove->terminal_id;
        $move->booking_no = $lastMove->booking_no;
        $move->remarkes = $lastMove->remarkes;
        $move->created_at = $lastMove->created_at;
        $move->updated_at = $lastMove->updated_at;
        $move->transshipment_port_id = $lastMove->transshipment_port_id;
        $move->booking_agent_id = $lastMove->booking_agent_id;
        $move->free_time = $lastMove->free_time;
        $move->container_status = $lastMove->container_status;
        $move->import_agent = $lastMove->import_agent;
        $move->free_time_origin = $lastMove->free_time_origin;
    }
    
    

    public function create()
    {
        $this->authorize(__FUNCTION__, Movements::class);
        $container_id = request()->input('container_id');
        $voyages = Voyages::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $vessels = Vessels::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $agents = Agents::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $bookings = Booking::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $containerstatus = ContainerStatus::orderBy('id')->get();

        if (isset($container_id)) {
            $container = Containers::find($container_id);
            $movement = Movements::where('container_id', $container_id)->with(
                'movementcode',
                'container',
                'containersType',
                'booking'
            )->orderBy('movement_date', 'desc')->orderBy('id', 'desc')->first();
            $container_type = $movement->container_type_id;
            // dd($container_type);
            if ($movement->movementcode['code'] == 'RCVC' || $movement->movementcode['code'] == 'DCHE' || $movement->movementcode['code'] == 'RCVE') {
                return view('containers.movements.create', [
                    'voyages' => $voyages,
                    'bookings' => $bookings,
                    'vessels' => $vessels,
                    'container' => $container,
                    'containersTypes' => $containersTypes,
                    'containersMovements' => $containersMovements,
                    'ports' => $ports,
                    'agents' => $agents,
                    'containerstatus' => $containerstatus,
                    'container_type' => $container_type,
                ]);
            } else {
                return view('containers.movements.create', [
                    'movement' => $movement,
                    'voyages' => $voyages,
                    'bookings' => $bookings,
                    'vessels' => $vessels,
                    'container' => $container,
                    'containersTypes' => $containersTypes,
                    'containersMovements' => $containersMovements,
                    'ports' => $ports,
                    'agents' => $agents,
                    'containerstatus' => $containerstatus,
                    'container_type' => $container_type,
                ]);
            }
        } else {
            $containers = Containers::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
            return view('containers.movements.create', [
                'voyages' => $voyages,
                'bookings' => $bookings,
                'vessels' => $vessels,
                'containers' => $containers,
                'containersTypes' => $containersTypes,
                'containersMovements' => $containersMovements,
                'ports' => $ports,
                'agents' => $agents,
                'containerstatus' => $containerstatus,
            ]);
        }
    }

    public function store(Request $request)
    {
        // dd($request->input());
        $this->authorize(__FUNCTION__, Movements::class);

        $request->validate([
            'movement' => 'required',
            'container_type_id' => 'required',
            'movement_id' => 'required',
            'movement_date' => 'required',
            'port_location_id' => 'required',
        ]);
        foreach ($request->movement as $move) {
            $containerNo = Containers::where('id', $move['container_id'])->pluck('code')->first();

            // Get All movements and sort it and get the last movement before this movement
            $movements = Movements::where('container_id', $move['container_id'])->orderBy(
                'movement_date',
                'desc'
            )->with('movementcode')->get();

            $new = $movements;
            $new = $new->groupBy('movement_date');

            foreach ($new as $key => $m) {
                $m = $m->sortByDesc('movementcode.sequence');
                $new[$key] = $m;
            }
            $new = $new->collapse();

            $movements = $new;
            $lastMove = $movements->where('movement_date', '<=', $request->input('movement_date'))->pluck(
                'movement_id'
            )->first();
            // End Get All movements and sort it and get the last movement before this movement

            $nextMoves = ContainersMovement::where('id', $lastMove)->pluck('next_move')->first();
            $nextMoves = explode(', ', $nextMoves);
            $moveCode = ContainersMovement::where('id', $request->movement_id)->pluck('code')->first();
            if (!$nextMoves[0] == null) {
                if (!in_array($moveCode, $nextMoves)) {
                    return redirect()->route('movements.create')->with(
                        'error',
                        'container number: ' . $containerNo . ' with movement: '
                        . $moveCode . ' not allowed!, the allowed movements for this container is ' . implode(
                            ", ",
                            $nextMoves
                        )
                    );
                }
            }
        }
        foreach ($request->movement as $move) {
            //dd($user->company_id);
            $user = Auth::user();
            $movem = Movements::create([
                'container_id' => $move['container_id'],
                'container_type_id' => $request->input('container_type_id'),
                'movement_id' => $request->input('movement_id'),
                'movement_date' => $request->input('movement_date'),
                'port_location_id' => $request->input('port_location_id'),
                'pol_id' => $request->input('pol_id'),
                'pod_id' => $request->input('pod_id'),
                'vessel_id' => $request->input('vessel_id'),
                'voyage_id' => $request->input('voyage_id'),
                'terminal_id' => $request->input('terminal_id'),
                'booking_no' => $request->input('booking_no'),
                'transshipment_port_id' => $request->input('transshipment_port_id'),
                'booking_agent_id' => $request->input('booking_agent_id'),
                'import_agent' => $request->input('import_agent'),
                'container_status' => $request->input('container_status'),
                'free_time' => $request->input('free_time'),
                'free_time_origin' => $request->input('free_time_origin'),
                'bl_no' => $request->input('bl_no'),
                'remarkes' => $request->input('remarkes'),
            ]);
            $movem->company_id = $user->company_id;
            $movem->save();
            //dd($movem);
        }

        // Movements::create($request->except('_token'));


        return redirect()->route('movements.index')->with('success', trans('Movement.created'));
    }

    public function show($id)
    {
        session(['returnUrl' => url()->previous()]);
        $this->authorize(__FUNCTION__, Movements::class);
        if (isset($id)) {
            $container_id = $id;
        } elseif (request('container_id') != null) {
            $container_id = request('container_id');
        } else {
            $container_id = null;
        }
        $movement = Movements::find($id);
        $container = Containers::find($id);
        $movementId = false;
        $movementsArray = false;
        $demurrages = Demurrage::where('company_id', Auth::user()->company_id)->get();

        if (request('plNo') == null) {
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id', $id)->with(
                'movementcode'
            );
        } else {
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id', $id)->with(
                'movementcode'
            )->where('bl_no', request('plNo'))->orderBy('movement_date', 'desc')->orderBy('id', 'desc');
        }

        if (request('voyage_id') != null) {
            $movements = $movements->where('voyage_id', request('voyage_id'));
        }
        if (request('port_location_id') != null) {
            $movements = $movements->whereIn('port_location_id', (array)request('port_location_id'));
        }
        if (request('movement_id') != null) {
            $movements = $movements->whereIn('movement_id', (array)request('movement_id'));
            $movementId = true;
        }
        if (request('booking_no') != null) {
            // $refNo = request()->booking_no;
            // $movements = $movements->whereHas('booking', function ($q) use ($refNo) {
            //     $q->where('ref_no', 'like', "%{$refNo}%");
            // });
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id', $id)->with(
                'movementcode'
            )->where('booking_no', request('booking_no'))->orderBy('movement_date', 'desc')->orderBy('id', 'desc');
        }
        if (request('movement_id') == null && request('port_location_id') == null) {
            // prepare Data for export
            $exportmovements = $movements->orderBy('movement_date', 'desc')->orderBy('id', 'desc')->with(
                'movementcode'
            )->get();

            $exportmovements = $exportmovements->groupBy('movement_date');

            foreach ($exportmovements as $key => $move) {
                $move = $move->sortByDesc('movementcode.sequence');
                $exportmovements[$key] = $move;
            }
            $exportmovements = $exportmovements->collapse();
            // prepare Data for show
            $movements = $movements->orderBy('movement_date', 'desc')->orderBy('id', 'desc')->with(
                'movementcode'
            )->paginate(30);
            $new = $movements->getCollection();
            $new = $new->groupBy('movement_date');

            foreach ($new as $key => $move) {
                $move = $move->sortByDesc('movementcode.sequence');
                $new[$key] = $move;
            }
            $new = $new->collapse();

            $movements = $movements->setCollection($new);
        } else {
            // prepare Data for export
            $exportmovements = Movements::where('container_id', $movements->first()->container_id)->orderBy(
                'movement_date',
                'desc'
            )
                ->orderBy('id', 'desc')->with('movementcode')->get();
            $exportmovements = $exportmovements->groupBy('movement_date');

            foreach ($exportmovements as $key => $move) {
                $move = $move->sortByDesc('movementcode.sequence');
                $exportmovements[$key] = $move;
            }
            $exportmovements = $exportmovements->collapse();
            // prepare Data for show
            $movements = Movements::where('container_id', $movements->first()->container_id)->orderBy(
                'movement_date',
                'desc'
            )
                ->orderBy('id', 'desc')->with('movementcode', 'booking')->paginate(30);
            $new = $movements->getCollection();
            $new = $new->groupBy('movement_date');

            foreach ($new as $key => $move) {
                $move = $move->sortByDesc('movementcode.sequence');
                $new[$key] = $move;
            }
            $new = $new->collapse();

            $movements = $movements->setCollection($new);
            $movements = $movements->first();

            $container_id = $movements->first()->container_id;
            if (request('movement_id') != null) {
                if (!in_array($movements->movement_id, (array)request('movement_id'))) {
                    $movements = [];
                    $movementsArray = true;
                }
            }
            if (request('port_location_id') != null) {
                if (!in_array($movements->port_location_id, (array)request('port_location_id'))) {
                    $movements = [];
                    $movementsArray = true;
                }
            }

            $movementId = true;
        }
        if (request('container_id')) {
            $container_id = request('container_id');
        }

        if (request('TillDate') && request('Triff_id') != null) {
            $tillDate = request('TillDate');
            $periods = Period::where('demurrage_id', request('Triff_id'))->get();

            $lastDCHF = $lastMove = Movements::where('container_id', $container_id)->where(
                'movement_id',
                ContainersMovement::where('code', 'DCHF')->pluck('id')->first()
            )->orderBy('movement_date', 'desc')->orderBy('id', 'desc')->first();
        } else {
            $tillDate = null;
            $periods = null;
            $lastDCHF = null;
        }

        $containers = Containers::where('id', $id)->first();
        $mytime = Carbon::now()->format('d-m-Y');
        if ($movementsArray == true) {
            $DCHF = 0;
            $RCVC = 0;
        } else {
            $DCHF = $movements->where(
                'movement_id',
                ContainersMovement::where('code', 'DCHF')->pluck('id')->first()
            )->count();
            $RCVC = $movements->where(
                'movement_id',
                ContainersMovement::where('code', 'RCVC')->pluck('id')->first()
            )->count();
        }
        session()->flash('items', $exportmovements);
        return view('containers.movements.show', [
            'movementsArray' => $movementsArray,
            'movementId' => $movementId,
            'periods' => $periods,
            'container_id' => $container_id,
            'tillDate' => $tillDate,
            'lastDCHF' => $lastDCHF,
            'id' => $id,
            'DCHF' => $DCHF,
            'RCVC' => $RCVC,
            'movement' => $movement,
            'container' => $container,
            'items' => $movements,
            'containers' => $containers,
            'mytime' => $mytime,
            'demurrages' => $demurrages,

        ]);
    }

    public function edit(Movements $movement)
    {
        $this->authorize(__FUNCTION__, Movements::class);
        $voyages = Voyages::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $vessels = Vessels::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $containers = Containers::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $agents = Agents::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $bookings = Booking::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $containerstatus = ContainerStatus::orderBy('id')->get();
        return view('containers.movements.edit', [
            'movement' => $movement,
            'voyages' => $voyages,
            'bookings' => $bookings,
            'vessels' => $vessels,
            'containers' => $containers,
            'containersTypes' => $containersTypes,
            'containersMovements' => $containersMovements,
            'ports' => $ports,
            'agents' => $agents,
            'containerstatus' => $containerstatus,
        ]);
    }

    public function update(Request $request, Movements $movement)
    {
        $request->validate([
            'movement_id' => 'required',
            'movement_date' => 'required',
            'port_location_id' => 'required',
            //     'voyage_id' =>[
            //     'required',
            //                     Rule::unique('movements')
            //                         ->where('vessel_id', $request->vessel_id)
            // ],
            'port_location_id' => 'required',
        ]);
        $this->authorize(__FUNCTION__, Movements::class);
        $movement->fill($request->except('_token'));
        $movementCode = ContainersMovement::where('id', $movement->movement_id)->pluck('code')->first();
        // $lastMove = Movements::where('container_id',$movement->container_id)
        //     ->where('movement_date','<',$movement->movement_date)->where('id','!=',$movement->id)
        //     ->orderBy('movement_date','desc')->orderBy('id','desc')->first();
        // Get All movements and sort it and get the last movement before this movement

        $movements = Movements::where('container_id', $movement->container_id)->orderBy('movement_date', 'desc')->with(
            'movementcode'
        )->get();

        $new = $movements;
        $new = $new->groupBy('movement_date');

        foreach ($new as $key => $move) {
            $move = $move->sortByDesc('movementcode.sequence');
            $new[$key] = $move;
        }
        $new = $new->collapse();

        $movements = $new;
        $lastMove = $movements->where('movement_date', '<=', $movement->movement_date)->where(
            'id',
            '!=',
            $movement->id
        )->pluck('movement_id')->first();
        // End Get All movements and sort it and get the last movement before this movement

        $allowedMovements = ContainersMovement::where('id', $lastMove)->pluck('next_move')->first();
        $msg = "The allowed movement is {$allowedMovements}";
        $allowedMovements = explode(", ", $allowedMovements);
        $url = $request->session()->get('returnUrl');
        if (in_array($movementCode, $allowedMovements)) {
            $movement->save();
            return redirect($url)->with('success', trans('Movement.updated.success'));
        }
        return back()->with('error', $msg);
    }

    public function destroy($id)
    {
        $movement = Movements::find($id);
        $movement->delete();
        return redirect()->back()->with('success', trans('Movement.deleted.success'));
    }
}
