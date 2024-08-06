<?php

namespace App\Http\Controllers\DententionStorageCalculation;

use Carbon\Carbon;
use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Voyages\Voyages;
use App\Models\Master\Containers;
use App\Models\Invoice\ChargesDesc;
use App\Http\Controllers\Controller;
use App\Models\Containers\Movements;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\DententionRequest;
use App\Models\Master\ContainersMovement;
use App\Services\BookingCalculationService;
use App\Exports\DetentionCalculationPeriodExport;

class CalculationPeriodController extends Controller
{
  
    private BookingCalculationService $service;

    public function __construct(BookingCalculationService $service)
    {
    $this->service = $service;
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('dentention.export');

    }

    /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function export(Request $request)
    {
        ob_end_clean();
        ob_start();
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();
        $containerIds = Movements::select('container_id')->whereIn('movement_id', [5, 6])
                        ->where('company_id', Auth::user()->company_id)
                        ->where('movement_date','>=',$fromDate)
                        ->where('movement_date','<=',$toDate)
                        ->groupBy('container_id', 'booking_no', 'bl_no')
                        ->havingRaw('COUNT(DISTINCT movement_id) = 2')
                        ->distinct()
                        ->pluck('container_id')->toArray(); 
        $containers = Containers::with('booking')->whereIn('id', $containerIds)->get();
        $payload['to_date'] =$request->to_date;
        $payload['apply_first_day']=1;
        $calculation = $this->service->containersCalculation( $containers,$payload);
        // return response()->json($calculation);
        $filename = 'CalculationPeriod_' . now()->timestamp . '.xls';
           return Excel::download( new DetentionCalculationPeriodExport($calculation),$filename,\Maatwebsite\Excel\Excel::XLS);
    }

}