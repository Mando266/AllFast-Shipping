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
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();
        $containerIds = $this->getContainerIds($fromDate, $toDate);
        if (empty($containerIds)) {
           return back()->with('error', "No RCVC Movement for in this Period $fromDate to $toDate");
        }
        $containers = Containers::with('booking')->whereIn('id', $containerIds)->get();
        $payload['to_date'] =$request->to_date;
        $payload['apply_first_day']=1;
        $calculation = $this->service->containersCalculation( $containers,$payload);
        if ($calculation instanceof \Illuminate\Http\RedirectResponse) {
            return $calculation;
        }
        return $this->downloadExcel($calculation);
    }

    private function getContainerIds($fromDate, $toDate)
    {
        return Movements::select('container_id')
            ->where('movement_id', 6)
            ->where('company_id', Auth::user()->company_id)
            ->whereBetween('movement_date', [$fromDate, $toDate])
            ->distinct()->pluck('container_id')->toArray();
    }

    private function downloadExcel($calculation)
    {
        $filename = 'CalculationPeriod_' . now()->timestamp . '.xls';
        return Excel::download(new DetentionCalculationPeriodExport($calculation), $filename,\Maatwebsite\Excel\Excel::XLS);
    }

}