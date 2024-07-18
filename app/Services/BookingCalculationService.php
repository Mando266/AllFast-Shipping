<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Master\Ports;
use App\Models\Booking\Booking;
use App\Models\Master\Containers;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\ContainersMovement;
use Illuminate\Database\Eloquent\Builder;

class BookingCalculationService
{

    public function booking(array $columns = ['*'], array $relations = []): Builder
    {
        return Booking::select($columns)
            ->where('booking_type', 'full')
            ->with($relations)
            ->orderBy('id', 'desc');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    public function create(array $payload)
    {
        if (in_array('all', $payload['container_ids'])) {
            $mov = Movements::where('booking_no', $payload['booking_no'])->where('company_id', Auth::user()->company_id)
                ->distinct()->get()->pluck('container_id')->toarray();
            $containers = Containers::whereIn('id', $mov)->get();
            $calculation = $this->containersCalculation($payload, $containers);
        } else {
            $containers = Containers::whereIn('id', $payload['container_ids'])->get();
            $calculation = $this->containersCalculation($payload, $containers);
        }
        if ($calculation instanceof \Illuminate\Http\RedirectResponse) {
            return $calculation;
        }

        return $calculation;
    }

    private function containersCalculation(array $payload, $containers)
    {
        $demurrage = $this->getDemurrageTriff($payload['booking_no'], isset($payload['is_storage']));
        if ($demurrage instanceof \Illuminate\Http\RedirectResponse) {
            return $demurrage;
        }

        $bookingFreeTime = isset($payload['is_storage']) ? 0 : $this->getBookingFreeTime($payload['booking_no']);
        $movementCompletedIds = $this->getMovementCompletedIds();
        $movementDCHFId = $this->getDCHFMovementId();
        $movementId = $payload['from'] ?? $movementDCHFId;
        $applyDays = 0;
        $applyDays += isset($payload['apply_first_day']) ? 1 : 0;
        $applyDays -= isset($payload['apply_last_day']) ? 1 : 0;
        $grandTotal = 0;
        $status = 'in_completed';
        $containerCalc = collect();

        foreach ($containers as $container) {
            $periodCalc = collect();
            $containerTotal = 0;
            $freeTime = $bookingFreeTime;
            $startMovement = $this->getStartMovement($container->id, $movementId, $payload['booking_no']);
            if ($startMovement instanceof \Illuminate\Http\RedirectResponse) {
                return $startMovement;
            }
            $startMovementDate = $startMovement->movement_date;
            $endMovement = $this->getEndMovement($payload, $container->id, $startMovementDate);
            if (in_array(optional($endMovement)->movement_id, $movementCompletedIds)) {
                $status = 'completed';
            }
            if (
                $endMovement == null ||
                ($payload['to_date'] < $endMovement->movement_date && !is_null($payload['to_date'])) ||
                !in_array(optional($endMovement)->movement_id, $movementCompletedIds)
                ) {
               
                $endMovementDate = $payload['to_date'];
            } else {
                $endMovementDate = $endMovement->movement_date;
            }
            $diffBetweenDates = 0;
            if ($endMovementDate) {
                $daysCount = Carbon::parse($endMovementDate)->startOfDay()->diffInDays(Carbon::parse($startMovementDate)->startOfDay());
            } else {
                $daysCount =today()->diffInDays(Carbon::parse($startMovementDate)->startOfDay());
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
                            $periodCalc->add($tempCollection);
                        } else {
                            $periodtotal = 0 * $tempDaysCount;
                            $containerTotal = $containerTotal + $periodtotal;
                            $tempCollection = collect([
                                'name' => $period->period,
                                'days' => $tempDaysCount,
                                'rate' => $period->rate,
                                'total' => $periodtotal,
                            ]);
                            $periodCalc->add($tempCollection);
                            $tempDaysCount = 0;
                        }
                    }
                } else {
                    if ($tempDaysCount != 0) {
                        if ($period->number_off_dayes < $tempDaysCount) {
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
                            $periodCalc->add($tempCollection);
                            $freeTime = 0;
                        } else {
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
                            $periodCalc->add($tempCollection);
                            $tempDaysCount = 0;
                            $freeTime = 0;
                        }
                    }
                }
            }
            $grandTotal = $grandTotal + $containerTotal;
            $tempCollection = collect([
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

    private function getEndMovement(array $payload, $containerId, $startMovementDate)
    {
        if ($payload['to_date'] == null && $payload['to'] == null) {
            $endMovement = Movements::where('container_id', $containerId)
                                    ->where('movement_date', '>', $startMovementDate)
                                    ->latest('movement_date')->first();
                                    
        } elseif ($payload['to_date'] != null && $payload['to'] == null) {
            $endMovement = Movements::where('container_id', $containerId)
                                    ->where('movement_date', '<=', $payload['to_date'])
                                    ->latest('movement_date')->first();
        } elseif ($payload['to_date'] == null && $payload['to'] != null) {
            $endMovement = Movements::where('container_id', $containerId)
                                    ->where('movement_id', $payload['to'])
                                    ->latest('movement_date')->first();
        } else {
            $endMovement = Movements::where('container_id', $containerId)
                                    ->where('movement_id', $payload['to'])
                                    ->where('movement_date', '<=', $payload['to_date'])
                                    ->latest('movement_date')->first();
        }

        return $endMovement;
    }

    private function getStartMovement($containerId, $movementId, $bookingNo)
    {
        $startMovement = Movements::where('container_id', $containerId)
            ->where('movement_id', $movementId)
            ->where('booking_no', $bookingNo)->first();
        if (!$startMovement) {
            $bookingCode = Booking::find($bookingNo)->ref_no;
            $movementCode = ContainersMovement::find($movementId)->code;
            return back()->with('error', "No $movementCode Movement for This Booking No $bookingCode ");
        }
        return $startMovement;

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
    private function getMovementCompletedIds()
    {
        $codes=['RCVC','LODF'];
        return ContainersMovement::whereIn('code', $codes)->pluck('id')->toarray();
    }

    /**
     * Get the demurrage tariff for a booking number.
     *
     * @param  string  $booking_no
     * @return \App\Models\Containers\Demurrage|null
     */
    private function getDemurrageTriff($booking_no, $is_storage)
    {
        $booking = $this->getBooking($booking_no);
        if (!$booking) {
            return null;
        }
        $cal_type = $is_storage ? 'STORAGE' : 'DETENTION';
        $type = strtoupper("{$booking->shipment_type}/{$cal_type}");
        $demurrage = Demurrage::where('is_storge', $type)
            ->where('port_id', $booking->discharge_port_id)
            ->with('slabs.periods')->first();

        if (!$demurrage) {
            $port = Ports::find($booking->discharge_port_id);
            return back()->with('error', "There is No ( $type ) Triff for BookingNo: {$booking->ref_no}  in port: $port->name( {$port->code} ) ");
        }
        return $demurrage;

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