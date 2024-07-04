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

        $movementErrors = MovementImportErrors::all();
        $container_id = request()->input('container_id');
        $filteredData = Movements::filter(new ContainersIndexFilter(request()))->orderBy('id')->groupBy(
            'container_id'
        )->with('container')->get();
        $myMoves = Movements::orderBy('movement_date', 'desc')->with('movementcode.containerstock')->get()->groupBy(
            'container_id'
        );

        // dd($filteredData);
        // $movements = Movements::where('movement_id', request('movement_id'))->paginate(30);
        $plNo = request()->input('bl_no');
        $movementsBlNo = Movements::where('company_id', Auth::user()->company_id)->select('bl_no')->distinct()->get(
        )->pluck('bl_no');
        $bookings = Booking::where('company_id', Auth::user()->company_id)->orderBy('id')->get();

        // remove element if last movement doesn't include movement_id or port_location_id
        if (request('movement_id') != null || request('port_location_id') != null) {
            // Get All movements and sort it and get the last movement before this movement
            $movements = Movements::filter(new ContainersIndexFilter(request()))->orderBy(
                'movement_date',
                'desc'
            )->with('movementcode.containerstock', 'container')->get();

            $new = $movements;
            $new = $new->groupBy('movement_date');

            foreach ($new as $key => $move) {
                $move = $move->sortByDesc('movementcode.sequence');
                $new[$key] = $move;
            }
            $new = $new->collapse();

            $movements = $new;
            $filteredData = $movements->unique('container_id');


            // End Get All movements and sort it and get the last movement before this movement
            if (request('movement_id') != null) {
                foreach ($filteredData as $key => $move) {
                    // Get All movements and sort it and get the last movement before this movement
                    $tempMovements = $myMoves[$move->container_id] ?? Movements::where(
                            'container_id',
                            $move->container_id
                        )->orderBy('movement_date', 'desc')->with('movementcode.containerstock')->get();

                    $new = $tempMovements;
                    $new = $new->groupBy('movement_date');

                    foreach ($new as $k => $move) {
                        $move = $move->sortByDesc('movementcode.sequence');
                        $new[$k] = $move;
                    }
                    $new = $new->collapse();

                    $tempMovements = $new;
                    $lastMove = $tempMovements->first();
                    // End Get All movements and sort it and get the last movement before this movement

                    // dump($lastMove->movement_id != request('movement_id'));
                    if (!in_array($lastMove->movement_id, (array)request('movement_id'))) {

                        unset($filteredData[$key]);
                    }
                }
            }
            if (request('port_location_id') != null) {
                foreach ($filteredData as $key => $move) {
                    // Get All movements and sort it and get the last movement before this movement
                    $tempMovements = $myMoves[$move->container_id] ?? Movements::where(
                            'container_id',
                            $move->container_id
                        )->orderBy('movement_date', 'desc')->with('movementcode.containerstock')->get();

                    $new = $tempMovements;
                    $new = $new->groupBy('movement_date');

                    foreach ($new as $k => $move) {
                        $move = $move->sortByDesc('movementcode.sequence');
                        $new[$k] = $move;
                    }
                    $new = $new->collapse();

                    $tempMovements = $new;
                    $lastMove = $tempMovements->first();
                    // End Get All movements and sort it and get the last movement before this movement
                    if (!in_array($lastMove->port_location_id, (array)request('port_location_id'))) {
                        unset($filteredData[$key]);
                    } else {
                        $move = $lastMove;
                    }
                }
            }
        }
        if (request('TillDate')) {
            $tillDate = request('TillDate');
            $periods = Period::where('demurrage_id', request('Triff_id'))->get();
            // Get All movements and sort it and get the last movement before this movement
            $movements = Movements::where('container_id', $move->container_id)->orderBy('movement_date', 'desc')->with(
                'movementcode.containerstock'
            )->get();

            $new = $movements;
            $new = $new->groupBy('movement_date');

            foreach ($new as $key => $move) {
                $move = $move->sortByDesc('movementcode.sequence');
                $new[$key] = $move;
            }
            $new = $new->collapse();

            $movements = $new;
            // End Get All movements and sort it and get the last movement before this movement
            $lastDCHF = $lastMove = $movements->where(
                'movement_id',
                ContainersMovement::where('code', 'DCHF')->pluck('id')->first()
            )->first();
        } else {
            $tillDate = null;
            $periods = null;
            $lastDCHF = null;
        }
        $temp = [];
        foreach ($filteredData as $element) {
            $id = $element->id;
            $temp[] = $id;
        }

        if ($filteredData->count() == 1) {
            $filteredData = $filteredData->first();
        }


        $movements = Movements::wherein('id', $temp)->where('company_id', Auth::user()->company_id)->orderBy(
            'movement_date',
            'desc'
        )->orderBy('id', 'desc')->groupBy('container_id')->with('container.containersOwner')->paginate(30);

        foreach ($movements as $move) {
            // Get All movements and sort it and get the last movement before this movement
            $tempMovements = $myMoves[$move->container_id] ?? Movements::where(
                    'container_id',
                    $move->container_id
                )->orderBy('movement_date', 'desc')->with('movementcode.containerstock')->get();

            $new = $tempMovements;
            $new = $new->groupBy('movement_date');

            foreach ($new as $key => $tempMove) {
                $tempMove = $tempMove->sortByDesc('movementcode.sequence');
                $new[$key] = $tempMove;
            }
            $new = $new->collapse();

            $tempMovements = $new;
            // End Get All movements and sort it and get the last movement before this movement

            if (request('bl_no') != null) {
                $tempMovements = $tempMovements->where('bl_no', request('bl_no'));
            }
            if (request('voyage_id') != null) {
                $tempMovements = $tempMovements->where('voyage_id', request('voyage_id'));
            }
            if (request('booking_no') != null) {
                $bookingNo = request('booking_no');
                $tempMovements = $tempMovements->filter(function ($item) use ($bookingNo) {
                    return preg_match("/$bookingNo/", $item['booking_no']);
                });
            }

            $lastMove = $tempMovements->first();

            if($lastMove != null)
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
        }

        // prepare export movements

        $exportMovements = Movements::where('company_id', Auth::user()->company_id)->wherein('id', $temp)->orderBy(
            'movement_date',
            'desc'
        )->orderBy('id', 'desc')->groupBy('container_id')->with(
            'container.containersOwner',
            'movementcode.containerstock'
        )->get();

//        dd($exportMovements->count());
        $exportTempMoves = Movements::where('company_id', Auth::user()->company_id)->orderBy(
            'movement_date',
            'desc'
        )->with('movementcode.containerstock')->get()->groupBy('container_id');
        foreach ($exportMovements as $move) {
            // Get All movements and sort it and get the last movement before this movement
            $tempMovements = $exportTempMoves[$move->container_id] ?? Movements::where(
                    'company_id',
                    Auth::user()->company_id
                )->where('container_id', $move->container_id)->orderBy('movement_date', 'desc')->with(
                    'movementcode.containerstock'
                )->get();

            $new = $tempMovements;
            $new = $new->groupBy('movement_date');

            foreach ($new as $key => $tempMove) {
                $tempMove = $tempMove->sortByDesc('movementcode.sequence');
                $new[$key] = $tempMove;
            }
            $new = $new->collapse();

            $tempMovements = $new;
            // End Get All movements and sort it and get the last movement before this movement

            if (request('bl_no') != null) {
                $tempMovements = $tempMovements->where('bl_no', request('bl_no'));
            }
            if (request('voyage_id') != null) {
                $tempMovements = $tempMovements->where('voyage_id', request('voyage_id'));
            }
            if (request('booking_no') != null) {
                $bookingNo = request('booking_no');
                $tempMovements = $tempMovements->filter(function ($item) use ($bookingNo) {
                    return preg_match("/$bookingNo/", $item['booking_no']);
                });
            }

            $lastMove = $tempMovements->first();

            if($lastMove != null){
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
        }

        // End of Export Movements

        $containers = Containers::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $lessor = Containers::where('company_id', Auth::user()->company_id)->where('description', '!=', null)
            ->orderBy('id')->select('description')->distinct()->get();
        $ports = Ports::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $voyages = Voyages::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $container_ownership = ContinerOwnership::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $containerstatus = ContainerStatus::orderBy('id')->get();
        $vessels = Vessels::where('company_id', Auth::user()->company_id)->orderBy('name')->get();
        // $items = Movements::where('company_id',Auth::user()->company_id)->wherein('id',$temp)->orderBy('movement_date','desc')->orderBy('id','desc')->groupBy('container_id')->with('container.containersOwner','movementcode.containerstock')->get();
        session()->flash('items', $exportMovements);
        if (count($temp) != 1) {
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
        } else {
            if ($container_id == null) {
                if ($movements->count() == 0) {
                    return view('containers.movements.index', [
                        'items' => $movements,
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
                // dd($movements);
                $container_id = Containers::where('id', $movements->first()->container_id)->pluck('id')->first();
            }
            $movement = Movements::find($container_id);
            $container = Containers::find($container_id);
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id', $container_id);

            $movementId = false;
            $movementsArray = false;

            // Check if we have movement code or port_location_id to get latest movement or not
            if (request('movement_id') == null && request('port_location_id') == null) {
                // prepare Data for export
                $exportmovements = $movements->orderBy('movement_date', 'desc')->orderBy('id', 'desc')->with(
                    'movementcode.containerstock',
                    'container'
                )->get();
                $exportmovements = $exportmovements->groupBy('movement_date');

                foreach ($exportmovements as $key => $move) {
                    $move = $move->sortByDesc('movementcode.sequence');
                    $exportmovements[$key] = $move;
                }
                $exportmovements = $exportmovements->collapse();
                // prepare Data for show
                $movements = $movements->orderBy('movement_date', 'desc')->orderBy('id', 'desc')->with(
                    'movementcode.containerstock'
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
                if (request('container_id') != null) {
                    // prepare Data for export
                    $exportmovements = Movements::where('container_id', request('container_id'))->orderBy(
                        'movement_date',
                        'desc'
                    )
                        ->orderBy('id', 'desc')->with('movementcode.containerstock')->get();

                    $exportmovements = $exportmovements->groupBy('movement_date');

                    foreach ($exportmovements as $key => $move) {
                        $move = $move->sortByDesc('movementcode.sequence');
                        $exportmovements[$key] = $move;
                    }
                    $exportmovements = $exportmovements->collapse();
                    // prepare Data for show
                    $movements = Movements::where('container_id', request('container_id'))->orderBy(
                        'movement_date',
                        'desc'
                    )
                        ->orderBy('id', 'desc')->with('movementcode.containerstock')->paginate(30);
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
                    $exportmovements = Movements::where('container_id', $container_id)->orderBy(
                        'movement_date',
                        'desc'
                    )->with('movementcode.containerstock')->get();
                    $exportmovements = $exportmovements->groupBy('movement_date');

                    foreach ($exportmovements as $key => $move) {
                        $move = $move->sortByDesc('movementcode.sequence');
                        $exportmovements[$key] = $move;
                    }
                    $exportmovements = $exportmovements->collapse();
                    // prepare Data for show
                    $movements = Movements::where('container_id', $container_id)->orderBy(
                        'movement_date',
                        'desc'
                    )->with('movementcode.containerstock')->paginate(30);
                    $new = $movements->getCollection();
                    $new = $new->groupBy('movement_date');

                    foreach ($new as $key => $move) {
                        $move = $move->sortByDesc('movementcode.sequence');
                        $new[$key] = $move;
                    }
                    $new = $new->collapse();

                    $movements = $movements->setCollection($new);
                }
                $movements = $movements->first();
                if (request('movement_id') != null) {
                    // dd($movements);
                    if (!in_array($movements->movement_id, (array)request('movement_id'))) {

                        $movementsArray = true;
                    }
                }
                if (request('port_location_id') != null) {
                    if (!in_array($movements->port_location_id, (array)request('port_location_id'))) {
                        $movementsArray = true;
                    }
                }
                $movementId = true;
            }
            $containers = Containers::where('id', $container_id)->first();


            if ($movementsArray == true) {
                $movements = [];
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
            $demurrages = Demurrage::get();
            $mytime = Carbon::now()->format('d-m-Y');
            is_array($container_id) ?? $container_id = $container_id[0];
            // dd($exportMovements);
            session()->flash('items', $movements);
            session(['returnUrl' => url()->previous()]);


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

    

        $containers = Containers::where('id', $id)->first();
      
        session()->flash('items', $exportmovements);
        return view('containers.movements.show', [
            'movementsArray' => $movementsArray,
            'movementId' => $movementId,
            'container_id' => $container_id,
            'id' => $id,
            'movement' => $movement,
            'container' => $container,
            'items' => $movements,
            'containers' => $containers,
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
            // You can re-enable this rule if needed
            // 'voyage_id' => [
            //     'required',
            //     Rule::unique('movements')->where('vessel_id', $request->vessel_id)
            // ],
        ]);
        
        $this->authorize(__FUNCTION__, Movements::class);
        $movement->fill($request->except('_token'));
        // Get the code for the current movement
        $movementCode = ContainersMovement::where('id', $movement->movement_id)->pluck('code')->first();
        // Get all movements for the container and sort by date, then by sequence
        $movements = Movements::where('container_id', $movement->container_id)
            ->orderBy('movement_date', 'desc')
            ->with('movementcode')
            ->get()
            ->groupBy('movement_date')
            ->map(function ($move) {
                return $move->sortByDesc('movementcode.sequence');
            })
            ->collapse();
    
        // Find the last movement before the current movement
        $lastMove = $movements->where('movement_date', '<=', $movement->movement_date)
            ->where('id', '!=', $movement->id)
            ->pluck('movement_id')
            ->first();
    
        // Determine allowed movements based on the last movement
        $allowedMovements = $lastMove 
            ? ContainersMovement::where('id', $lastMove)->pluck('next_move')->first()
            : null;
    
        if ($allowedMovements) {
            $msg = "The allowed movement is {$allowedMovements}";
            $allowedMovements = explode(", ", $allowedMovements);
        }
    
        // Validate the movement sequence if previous movements exist
        if (!$lastMove || in_array($movementCode, $allowedMovements)) {
            $movement->save();
            $url = $request->session()->get('returnUrl');
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
