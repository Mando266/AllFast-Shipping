<?php

namespace App\Http\Controllers\DententionStorageCalculation;

use App\Http\Controllers\Controller;
use App\Http\Requests\DententionRequest;
use App\Models\Master\ContainersMovement;
use App\Services\BookingCalculationService;

class InvoiceController extends Controller
{
    private BookingCalculationService $service;

    public function __construct(BookingCalculationService $service)
    {
        $this->service = $service;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
    dd($request->all(),$id);
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