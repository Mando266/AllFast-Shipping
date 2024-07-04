<?php

namespace App\Http\Controllers\Dentention;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        $apply_first_day = isset($request['apply_first_day']) ? 1 : 0;
        $apply_last_day = isset($request['apply_last_day']) ? 1 : 0;
        $movementDCHFId = $this->getDCHFMovementId();
        $movementRCVCId = $this->getRCVCMovementId();
        $demurrage = $this->getDemurrageTriff($request->booking_no);
        $bookingFreeTime = $this->getBookingFreeTime($request->booking_no);
        $containerCalc = collect();
        $status = 'completed';
        if (in_array('all', $request->container_ids)) {
            $mov = Movements::where('booking_no', $request->booking_no)->where('company_id', Auth::user()->company_id)
                ->distinct()->get()->pluck('container_id')->toarray();
            $containers = Containers::whereIn('id', $mov)->get();
            $grandTotal = 0;
            foreach ($containers as $container) {
                $periodCalc = collect();
                $containerTotal = 0;
                $startMovement = Movements::where('container_id', $container->id)
                                            ->where('movement_id', request()->from ?? $movementDCHFId)
                                            ->where('booking_no', $request->booking_no)->first();
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
                if ($endMovement == null || ($request->to_date < $endMovement->movement_date && !is_null($request->to_date))) {
                    $endMovementDate = $request->to_date;
                } else {
                    $endMovementDate = $endMovement->movement_date;
                }                
                $diffBetweenDates = 0;
                if ($endMovementDate) {
                    $daysCount = Carbon::parse($endMovementDate)->diffInDays($startMovementDate);
                } else {
                    $status = 'in_completed';
                    $daysCount = Carbon::parse(now())->diffInDays($startMovementDate);
                }
                $daysCount = $daysCount + $apply_first_day + $apply_last_day;
                $tempDaysCount = $daysCount;
                $slab = $demurrage->slabs()->firstWhere('container_type_id', $container->container_type_id);
                foreach (optional($slab)->periods as $period) {
                        if ($bookingFreeTime > $period->number_off_dayes) {
                            if ($tempDaysCount != 0) {
                                if ($period->number_off_dayes < $tempDaysCount) {
                                    $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                    $bookingFreeTime = $bookingFreeTime - $period->number_off_dayes;
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
                                    $days = $period->number_off_dayes - $bookingFreeTime;
                                    $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
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
                                            $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
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
                                    $bookingFreeTime = 0;
                                } else {
                                    // remaining days less than period days
                                    $days = $tempDaysCount - $bookingFreeTime;
                                    $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
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
                                            $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
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
                                    $bookingFreeTime = 0;
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
                    'from' =>  $startMovementDate,
                    'to' => $endMovementDate,
                    'from_code' => optional(optional($startMovement)->movementcode)->code,
                    'to_code' => optional(optional($endMovement)->movementcode)->code,
                    'total' => $containerTotal,
                    'periods' => $periodCalc,
                ]);
              
                $containerCalc->add($tempCollection);
            }
        } else {
            $containers = Containers::whereIn('id', $request->container_ids)->get();
            $grandTotal = 0;
            foreach ($containers as $container) {
                $periodCalc = collect();
                $containerTotal = 0;
                $startMovement = Movements::where('container_id', $container->id)->where('movement_id', request()->from ?? $movementDCHFId)
                    ->first();
                $startMovementDate = $startMovement->movement_date;

                if ($request->to_date == null && $request->to == null) {
                    $endMovement = Movements::where('container_id', $container->id)
                        ->where('movement_date', '>=', $startMovementDate)->oldest()->first();

                } elseif ($request->to_date == null) {
                    $endMovement = Movements::where('container_id', $container->id)->where('movement_id', $movementRCVCId)
                        ->where('movement_date', '>', $startMovementDate)->oldest()->first();
                } else {
                    $endMovement = Movements::where('container_id', $container->id)->where('movement_id', $request->to)
                        ->where('movement_date', '<=', $request->to_date)->oldest()->first();
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
                    $status = 'in_completed';
                    $daysCount = Carbon::parse(now())->diffInDays($startMovementDate);
                }

                $daysCount = $daysCount + $apply_first_day + $apply_last_day;
                $tempDaysCount = $daysCount;
                $slab = $demurrage->slabs()->firstWhere('container_type_id', $container->container_type_id);

                foreach ($slab->periods as $period) {
                    if (request()->service == 3 || request()->service == 1) {
                        //we are in the free time period
                        if ($bookingFreeTime > $period->number_off_dayes) {
                            if ($tempDaysCount != 0) {
                                if ($period->number_off_dayes < $tempDaysCount) {
                                    // remaining days more than period days
                                    $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                    $bookingFreeTime = $bookingFreeTime - $period->number_off_dayes;
                                    $shownDays = $period->number_off_dayes;
                                    if ($diffBetweenDates != 0) {
                                        $shownDays = $shownDays - $diffBetweenDates;
                                        $shownDays = $shownDays < 0 ? 0 : $shownDays;
                                        $diffBetweenDates = $diffBetweenDates - $period->number_off_dayes;
                                        $diffBetweenDates = $diffBetweenDates < 0 ? 0 : $diffBetweenDates;
                                    }
                                    $periodtotal = 0 * $period->number_off_dayes;
                                    $containerTotal = $containerTotal + $periodtotal;
                                    $tempCollection = collect([
                                        'name' => $period->period,
                                        'days' => $shownDays,
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
                                    $days = $period->number_off_dayes - $bookingFreeTime;
                                    $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
                                    $shownDays = $period->number_off_dayes;
                                    if ($diffBetweenDates != 0) {
                                        if ($diffBetweenDates >= $period->number_off_dayes) {
                                            $diffBetweenDates = $diffBetweenDates - $period->number_off_dayes;
                                            $days = 0;
                                            $shownDays = 0;
                                            $periodtotal = 0;
                                        } else {
                                            $shownDays = $shownDays - $diffBetweenDates;
                                            $days = $days - $diffBetweenDates;
                                            $diffBetweenDates = 0;
                                            $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
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
                                    $bookingFreeTime = 0;
                                } else {
                                    // remaining days less than period days
                                    $days = $tempDaysCount - $bookingFreeTime;
                                    $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
                                    if ($diffBetweenDates != 0) {
                                        if ($diffBetweenDates >= $tempDaysCount) {
                                            $diffBetweenDates = $diffBetweenDates - $tempDaysCount;
                                            $days = 0;
                                            $periodtotal = 0;
                                        } else {
                                            $diffBetweenDates = 0;
                                            $days = $days - $diffBetweenDates;
                                            $periodtotal = (0 * $bookingFreeTime) + ($period->rate * $days);
                                        }
                                        $diffBetweenDates = $diffBetweenDates < 0 ? 0 : $diffBetweenDates;
                                    }
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
                                    $bookingFreeTime = 0;
                                }
                            }
                        }
                    } else {
                        if ($tempDaysCount != 0) {
                            if ($period->number_off_dayes < $tempDaysCount) {
                                // remaining days more than period days
                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                $periodtotal = $period->rate * $period->number_off_dayes;
                                if ($diffBetweenDates != 0) {
                                    if ($diffBetweenDates >= $period->number_off_dayes) {
                                        $diffBetweenDates = $diffBetweenDates - $period->number_off_dayes;
                                        $periodtotal = 0;
                                    } else {
                                        $periodtotal = $period->rate * ($period->number_off_dayes - $diffBetweenDates);
                                        $diffBetweenDates = 0;
                                    }
                                    $diffBetweenDates = $diffBetweenDates < 0 ? 0 : $diffBetweenDates;
                                }
                                $containerTotal = $containerTotal + $periodtotal;
                                $tempCollection = collect([
                                    'name' => $period->period,
                                    'days' => $period->number_off_dayes,
                                    'rate' => $period->rate,
                                    'total' => $periodtotal,
                                ]);
                                // Adding period
                                $periodCalc->add($tempCollection);
                            } else {
                                // remaining days less than period days
                                $periodtotal = $period->rate * $tempDaysCount;
                                if ($diffBetweenDates != 0) {
                                    if ($diffBetweenDates >= $tempDaysCount) {
                                        $diffBetweenDates = $diffBetweenDates - $tempDaysCount;
                                        $periodtotal = 0;
                                    } else {
                                        $periodtotal = $period->rate * ($tempDaysCount - $diffBetweenDates);
                                        $diffBetweenDates = 0;
                                    }
                                    $diffBetweenDates = $diffBetweenDates < 0 ? 0 : $diffBetweenDates;
                                }
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
                    }
                }

                // Adding Container with periods
                $grandTotal = $grandTotal + $containerTotal;
                $tempCollection = collect([
                    // 'bl_no' => $endMovement->bl_no ?? '',
                    'container_no' => $container->code,
                    'status' => trans("home.$status"),
                    'container_type' => $container->containersTypes->name,
                    'from' =>  $startMovementDate,
                    'to' => $endMovementDate,
                    'from_code' => optional(optional($startMovement)->movementcode)->code,
                    'to_code' => $endMovement != null ? (optional($endMovement)->movement_date != null ? $endMovement->movementcode->code : $endMovement) : now(),
                    'total' => $containerTotal,
                    'periods' => $periodCalc,
                ]);
                $containerCalc->add($tempCollection);
            }
        }

        $calculation = collect([
            'grandTotal' => $grandTotal,
            'currency' => $demurrage->currency,
            'containers' => $containerCalc,
        ]);
        $data = [
            'calculation' => $calculation,
            'input' => $request->input(),
        ];

        return redirect()->route('dententions.index')->with($data);
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