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
use App\Services\StorageExportCalculationService;
use App\Exports\StorageCalculationPeriodExport;

class StorageCalculationPeriodController extends Controller
{

    private StorageExportCalculationService $service;

    public function __construct(StorageExportCalculationService $service)
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
        $movementsCode = ContainersMovement::orderBy('id')->get();
        return view('storage_cal.export', compact('movementsCode'));

    }

    /**
    * Store a newly created resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function export(Request $request)
    {
        $containerIds = $this->getContainerIds($request);
        if (empty($containerIds)) {
            $codes = implode('/',$request->to_code);
        return back()->with('error', "No $codes Movement for in this Period $request->from_date to $request->to_date");
        }
        $containers = Containers::with('booking')->whereIn('id', $containerIds)->get();
        $payload=[
            'from_code'=>$request->from_code,
            'to_code'=>$request->to_code,
            'from_date'=>$request->from_date,
            'to_date'=>$request->to_date,
            'shipment_type'=>$request->shipment_type,
            'apply_first_day'=>1,
            'is_storage'=>1,
            ];
        $calculation = $this->service->containersCalculation( $containers,$payload);
        if ($calculation instanceof \Illuminate\Http\RedirectResponse) {
            return $calculation;
        }
        return $this->downloadExcel($calculation);
    }

    private function getContainerIds(Request $request)
    {
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();
        $movementIds=$this->getMovementIds($request->to_code);
        return Movements::select('container_id')
            ->whereHas('booking', function ($query) use ($request) {
                $query->whereIn('shipment_type', ['Export', 'Import']);
                if ($request->booking_type) {
                    $query->where('booking_type',$request->booking_type);
                }
                if ($request->shipment_type) {
                    $query->where('shipment_type', $request->shipment_type);
                }
            })
            ->whereIn('movement_id', $movementIds)
            ->where('company_id', Auth::user()->company_id)
            ->whereBetween('movement_date', [$fromDate, $toDate])
            ->distinct('container_id')
            ->pluck('container_id',)->toArray();
    }

    private function getMovementIds($codes)
    {
        return ContainersMovement::whereIn('code', $codes)->pluck('id')->toarray();
    }

    private function downloadExcel($calculation)
    {
        $filename = 'ExportStorage_' . now()->timestamp . '.xls';
        return Excel::download(new StorageCalculationPeriodExport($calculation), $filename,\Maatwebsite\Excel\Excel::XLS);
    }

}