<?php

namespace App\Http\Controllers\DententionStorageCalculation;

use App\Http\Controllers\Controller;
use App\Http\Requests\DententionRequest;
use App\Models\Master\ContainersMovement;
use App\Services\BookingCalculationService;

class DententionController extends Controller
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
        $movementsCode = ContainersMovement::orderBy('id')->get();
        $bookings = $this->service->booking(['id', 'ref_no'])->get();

        return view('dentention.index', compact('bookings', 'movementsCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DententionRequest $request)
    {
        $calculation = $this->service->create($request->validated());
        if ($calculation instanceof \Illuminate\Http\RedirectResponse) {
            return $calculation;
        }
        return redirect()->route('dententions.index')->with([
            'calculation' => $calculation,
            'input' => $request->input(),
        ]);
    }

}