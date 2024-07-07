<?php

namespace App\Http\Controllers\Dentention;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Master\Containers;
use App\Http\Controllers\Controller;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DententionRequest;
use App\Models\Master\ContainersMovement;

class DententionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $movementsCode = ContainersMovement::orderBy('id')->get();
        $bookings = Booking::select('id', 'ref_no')
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('id')
            ->get();

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
        dd($request->all(),$request->validated());
        $bookingNo = $request->booking_no;
        if (in_array('all', $request->container_ids)) {
            $mov = Movements::where('booking_no', $bookingNo)->where('company_id', Auth::user()->company_id)
                ->distinct()->get()->pluck('container_id')->toarray();
            $containers = Containers::whereIn('id', $mov)->get();
            $calculation = $this->containersCalculation($request, $containers);
        } else {
            $containers = Containers::whereIn('id', $request->container_ids)->get();
            $calculation = $this->containersCalculation($request, $containers);
        }

        return redirect()->route('dententions.index')->with([
            'calculation' => $calculation,
            'input' => $request->input(),
        ]);
    }

    private function containersCalculation(Request $request, $containers)
    {
        $bookingNo = $request->booking_no;
        $demurrage = $this->getDemurrageTriff($bookingNo);
        $bookingFreeTime = $this->getBookingFreeTime($bookingNo);
        $movementRCVCId = $this->getRCVCMovementId();
        $movementDCHFId = $this->getDCHFMovementId();
        $movementId = request()->from ?? $movementDCHFId;
        $applyDays = 1;
        $applyDays += isset($request['apply_first_day']) ? 1 : 0;
        $applyDays -= isset($request['apply_last_day']) ? 1 : 0;

        $grandTotal = 0;
        $status = 'in_completed';
        $containerCalc = collect();

        foreach ($containers as $container) {
            $periodCalc = collect();
            $containerTotal = 0;
            $freeTime = $bookingFreeTime;
            $startMovement = Movements::where('container_id', $container->id)
                ->where('movement_id', $movementId)
                ->where('booking_no', $bookingNo)->first();
            if (!$startMovement) {
                $bookingCode = Booking::find($bookingNo)->ref_no;
                return back()->with('error', "No DCHF Movement for This Booking No $bookingCode ");
            }
            $startMovementDate = $startMovement->movement_date;
            if ($request->to_date == null && $request->to == null) {
                $endMovement = Movements::where('container_id', $container->id)
                // ->where('movement_id', $movementRCVCId)
                    ->where('movement_date', '>', $startMovementDate)->oldest()->first();
            } elseif ($request->to_date != null && $request->to == null) {
                $endMovement = Movements::where('container_id', $container->id)
                    ->where('movement_date', '>', $request->to_date)->oldest()->first();
            } elseif ($request->to_date == null && $request->to != null) {
                $endMovement = Movements::where('container_id', $container->id)
                    ->where('movement_id', $request->to)
                    ->oldest()->first();
            } else {
                $endMovement = Movements::where('container_id', $container->id)->where('movement_id', $request->to)
                    ->where('movement_date', '<=', $request->to_date)->oldest()->first();
            }

            if (optional($endMovement)->movement_id == $movementRCVCId) {
                $status = 'completed';
            }

            if ($endMovement == null || ($request->to_date < $endMovement->movement_date && !is_null($request->to_date))) {
                $endMovementDate = $request->to_date;
            } else {
                $endMovementDate = $endMovement->movement_date;
            }
            $diffBetweenDates = 0;

            if ($endMovementDate) {
                $daysCount = Carbon::parse($endMovementDate)->diffInDays($startMovementDate);
            } else {
                $daysCount = Carbon::parse(now())->diffInDays($startMovementDate);
            }
            $daysCount = $daysCount + $applyDays;
            $tempDaysCount = $daysCount;
            $slab = $demurrage->slabs()->firstWhere('container_type_id', $container->container_type_id);
            foreach (optional($slab)->periods as $period) {
                if ($freeTime > $period->number_off_dayes) {
                    if ($tempDaysCount != 0) {
                        if ($period->number_off_dayes < $tempDaysCount) {
                            $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                            $freeTime = $freeTime - $period->number_off_dayes;
                            $days = $period->number_off_dayes;

                            if ($diffBetweenDates != 0) {
                                $days = $days - $diffBetweenDates;
                                $days = $days < 0 ? 0 : $days;
                                $diffBetweenDates = $diffBetweenDates - $period->number_off_dayes;
                                $diffBetweenDates = $diffBetweenDates < 0 ? 0 : $diffBetweenDates;
                            }
                            $periodtotal = 0 * $period->number_off_dayes;
                            $containerTotal = $containerTotal + $periodtotal;
                            $tempCollection = collect([
                                'name' => $period->period,
                                'days' => $days,
                                'rate' => $period->rate,
                                'total' => $periodtotal,
                            ]);
                            // Adding period
                            $periodCalc->add($tempCollection);
                        } else {
                            // remaining days less than period days
                            $periodtotal = 0 * $tempDaysCount;
                            $containerTotal = $containerTotal + $periodtotal;
                            $tempCollection = collect([
                                'name' => $period->period,
                                'days' => $tempDaysCount,
                                'rate' => $period->rate,
                                'total' => $periodtotal,
                            ]);
                            // Adding period
                            $periodCalc->add($tempCollection);
                            $tempDaysCount = 0;
                        }
                    }
                } else {
                    if ($tempDaysCount != 0) {
                        if ($period->number_off_dayes < $tempDaysCount) {
                            // remaining days more than period days
                            $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                            $days = $period->number_off_dayes - $freeTime;
                            $periodtotal = (0 * $freeTime) + ($period->rate * $days);
                            $shownDays = $period->number_off_dayes;
                            if ($diffBetweenDates != 0) {
                                if ($diffBetweenDates >= $period->number_off_dayes) {
                                    $diffBetweenDates = $diffBetweenDates - $period->number_off_dayes;
                                    $days = 0;
                                    $shownDays = 0;
                                    $periodtotal = 0;
                                } else {
                                    $days = $days - $diffBetweenDates;
                                    $shownDays = $shownDays - $diffBetweenDates;
                                    $diffBetweenDates = 0;
                                    $periodtotal = (0 * $freeTime) + ($period->rate * $days);
                                }
                                $diffBetweenDates = $diffBetweenDates < 0 ? 0 : $diffBetweenDates;
                            }
                            $containerTotal = $containerTotal + $periodtotal;
                            $tempCollection = collect([
                                'name' => $period->period,
                                'days' => $shownDays,
                                'rate' => $period->rate,
                                'total' => $periodtotal,
                            ]);
                            // Adding period
                            $periodCalc->add($tempCollection);
                            $freeTime = 0;
                        } else {
                            // remaining days less than period days
                            $days = $tempDaysCount - $freeTime;
                            $periodtotal = (0 * $freeTime) + ($period->rate * $days);
                            $shownDays = $tempDaysCount;
                            if ($diffBetweenDates != 0) {
                                if ($diffBetweenDates >= $tempDaysCount) {
                                    $diffBetweenDates = $diffBetweenDates - $tempDaysCount;
                                    $days = 0;
                                    $shownDays = 0;
                                    $periodtotal = 0;
                                } else {
                                    $days = $days - $diffBetweenDates;
                                    $shownDays = $shownDays - $diffBetweenDates;
                                    $diffBetweenDates = 0;
                                    $periodtotal = (0 * $freeTime) + ($period->rate * $days);
                                }
                                $diffBetweenDates = $diffBetweenDates < 0 ? 0 : $diffBetweenDates;
                            }
                            $containerTotal = $containerTotal + $periodtotal;
                            $tempCollection = collect([
                                'name' => $period->period,
                                'days' => $shownDays,
                                'rate' => $period->rate,
                                'total' => $periodtotal,
                            ]);
                            // Adding period
                            $periodCalc->add($tempCollection);
                            $tempDaysCount = 0;
                            $freeTime = 0;
                        }
                    }
                }
            }
            // Adding Container with periods
            $grandTotal = $grandTotal + $containerTotal;
            $tempCollection = collect([
                // 'bl_no' => $endMovement->bl_no ?? '',
                'container_no' => $container->code,
                'status' => trans("home.$status"),
                'container_type' => $container->containersTypes->name,
                'from' => $startMovementDate,
                'to' => $endMovementDate,
                'from_code' => optional(optional($startMovement)->movementcode)->code,
                'to_code' => optional(optional($endMovement)->movementcode)->code,
                'total' => $containerTotal,
                'periods' => $periodCalc,
            ]);

            $containerCalc->add($tempCollection);
        }

        return collect([
            'grandTotal' => $grandTotal,
            'currency' => optional($demurrage)->currency,
            'containers' => $containerCalc,
        ]);

    }
    /**
     * Get the discharge port ID for a booking.
     *
     * @param  int  $id
     * @return int|null
     */
    private function getBooking($id)
    {
        return Booking::find($id);
    }

    /**
     * Get the ID of the DCHF movement.
     *
     * @return int|null
     */
    private function getDCHFMovementId()
    {
        return ContainersMovement::where('code', 'DCHF')->first()->id ?? null;
    }
    /**
     * Get the ID of the RCVC movement.
     *
     * @return int|null
     */
    private function getRCVCMovementId()
    {
        return ContainersMovement::where('code', 'RCVC')->first()->id ?? null;
    }

    /**
     * Get the demurrage tariff for a booking number.
     *
     * @param  string  $booking_no
     * @return \App\Models\Containers\Demurrage|null
     */
    private function getDemurrageTriff($booking_no)
    {
        $booking = $this->getBooking($booking_no);
        if (!$booking) {
            return null;
        }
        return Demurrage::where('port_id', $booking->discharge_port_id)->with('slabs.periods')->first();
    }
    private function getBookingFreeTime($booking_no)
    {
        $booking = $this->getBooking($booking_no);
        if (!$booking) {
            return null;
        }

        return ($booking->free_time > 0) ? $booking->free_time : optional($booking->quotation)->import_detention;

    }
}