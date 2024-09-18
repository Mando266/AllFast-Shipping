<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Master\Ports;
use App\Models\Booking\Booking;
use App\Models\Master\Containers;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\ContainersTypes;
use App\Models\Master\ContainersMovement;
use Illuminate\Database\Eloquent\Builder;

class DetentionExportCalculationService extends BookingCalculationService
{

  
    public function containersCalculation($containers,array $payload =[])
    {
        $movementCompletedIds = $this->getMovementCompletedIds();
        $applyDays = 0;
        $applyDays += isset($payload['apply_first_day']) ? 1 : 0;
        $applyDays -= isset($payload['apply_last_day']) ? 1 : 0;
        $grandTotal = 0;
        $status = 'in_completed';
        $to_date=isset($payload['to_date'])?Carbon::parse($payload['to_date'])->endOfDay():null;
        $containerCalc = collect();
        foreach ($containers as $container) {
            $booking_no=$this->getBookingNoMovement($payload,$container->id)->booking_no;
            $movementId=$this->getBookingNoStartMovement($payload,$booking_no);
            $payload['booking_no']=$booking_no;
            $demurrage = $this->getDemurrageTriff($booking_no, isset($payload['is_storage']));
            if ($demurrage instanceof \Illuminate\Http\RedirectResponse) {
                    return $demurrage;
            }
            $periodCalc = collect();
            $containerTotal = 0;
            $freeTime = isset($payload['is_storage']) ? 0 : $this->getBookingFreeTime($booking_no,$container);
            $free_time = $freeTime ?? 0;
            $startMovement = $this->getStartMovement($container->id, $movementId, $booking_no);
            if ($startMovement instanceof \Illuminate\Http\RedirectResponse) {
                return $startMovement;
            }
            $startMovementDate = $startMovement->movement_date;
            $endMovement = $this->getEndMovement($payload, $container->id, $startMovementDate);
            $lastMovement = $this->getLastMovement($payload,$container->id);
           

            if (in_array(optional($endMovement)->movement_id, $movementCompletedIds)) {
                $status = 'completed';
            }
            if (
                $endMovement == null ||
                ($to_date <= $endMovement->movement_date && !is_null($to_date)) ||
                (!in_array(optional($endMovement)->movement_id, $movementCompletedIds))
            ) {
                if(in_array(optional($lastMovement)->movement_id, $movementCompletedIds) && $endMovement ){
                    $endMovementDate = optional($endMovement)->movement_date;
                }else{
                    $endMovementDate = $to_date;
                }
            } else {
                $endMovementDate = $endMovement->movement_date;
            }



            
            if ($endMovementDate) {
                $daysCount = Carbon::parse($endMovementDate)->startOfDay()->diffInDays(Carbon::parse($startMovementDate)->startOfDay());
            } else {
                $daysCount = today()->diffInDays(Carbon::parse($startMovementDate)->startOfDay());
            }
            $daysCount = $daysCount + $applyDays;
            $tempDaysCount = $daysCount;
            $diffBetweenDates = 0;
            $slab = $demurrage->slabs()->firstWhere('container_type_id', $container->container_type_id);
            if (!$slab) {
                $containersType = ContainersTypes::find($container->container_type_id);
                return back()->with('error', "There is No slabs to {$containersType->name} in port ".optional($demurrage->ports)->name);
            }

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
                            $days = $days < 0 ? 0 : $days;
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
                'bl_no' => $startMovement->bl_no,
                'status' => trans("home.$status"),
                'container_type' => $container->containersTypes->name,
                'from' => $startMovementDate,
                'to' => $endMovementDate?? today(),
                'from_code' => optional(optional($startMovement)->movementcode)->code,
                'to_code' => optional(optional($endMovement)->movementcode)->code ?? trans("home.no_movement"),
                'total' => $containerTotal,
                'daysCount' => $daysCount,
                'freeTime' => $free_time,
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

    private function getBookingNoStartMovement(array $payload,$booking_no)
    {
        $code='DCHF';
        $booking = $this->getBooking($booking_no);
        if (!$booking) { return null; }
        if ($payload['shipment_type'] == 'Export') {
            $code = 'RCVS';
        }
        return ContainersMovement::where('code', $code)->first()->id;
    }
    
    private function getBookingNoMovement(array $payload,$containerId)
    {
        $fromDate = Carbon::parse($payload['from_date'])->startOfDay();
        $toDate = Carbon::parse($payload['to_date'])->endOfDay();
        $codes=($payload['shipment_type'] == 'Import')? ['RSTR','RCVC'] :['LODF'];
        $movement_id= $this->getMovementIds($codes);
        return Movements::select('booking_no')
                ->whereIn('movement_id', $movement_id)
                ->where('container_id', $containerId)
                ->where('company_id', Auth::user()->company_id)
                ->whereBetween('movement_date', [$fromDate, $toDate])
                ->latest('movement_date')->first();
    }
    
    private function getLastMovement(array $payload,$containerId)
    {
        return  Movements::where('container_id', $containerId)
                ->where('booking_no', $payload['booking_no'])
                ->latest('movement_date')->first();
    }
    
    private function getEndMovement(array $payload, $containerId, $startMovementDate)
    {
        $to_date=isset($payload['to_date'])?Carbon::parse($payload['to_date'])->endOfDay():null;
        $to=isset($payload['to'])?$payload['to']:null;
        $booking_no=isset($payload['booking_no'])?$payload['booking_no']:null;
        $endMovement = Movements::where('container_id', $containerId)
            ->where('booking_no', $booking_no);

        if ($to_date == null && $to == null) {

            $endMovement->where('movement_date', '>', $startMovementDate);

        } elseif ($to_date != null && $to == null) {
            $endMovement->where('movement_date', '>', $startMovementDate)
                ->where('movement_date', '<=', $to_date)
                ;
        } elseif ($to_date == null && $to != null) {
            $endMovement->where('movement_id', $to)
                ;
        } else {
            $endMovement->where('movement_id', $to)
                ->where('movement_date', '>', $startMovementDate)
                ->where('movement_date', '<=', $to_date)
                ;
        }
        return $endMovement->latest('movement_date')->first();
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
        return Booking::with('quotation')->find($id);
    }

    /**
     * Get the ID of the RCVC movement.
     *
     * @return int|null
     */
    private function getMovementCompletedIds()
    {
        $codes = ['RCVC', 'LODF','RSTR'];
        return $this->getMovementIds($codes);
    }
    
    private function getMovementIds($codes)
    {
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
        $portId = $booking->shipment_type == 'Export' ? $booking->load_port_id : $booking->discharge_port_id;

        $demurrage = Demurrage::where('is_storge', $type)
            ->where('port_id', $portId)
            ->with('slabs.periods')->first();

        if (!$demurrage) {
            $port = Ports::find($portId);
            return back()->with('error', "There is No ( $type ) Triff for BookingNo: {$booking->ref_no}  in port: $port->name( {$port->code} ) ");
        }
        return $demurrage;

    }

    private function getBookingFreeTime($booking_no,Containers $container)
    {
        $booking = $this->getBooking($booking_no);
        if (!$booking) {
            return null;
        }
        $quotation=optional(optional($booking->quotation)->quotationDesc)->firstWhere('equipment_type_id',$container->container_type_id);
            if($quotation){
                return $quotation->free_time;
            }
        $bookingDetails=optional($booking->bookingContainerDetails)->firstWhere('container_id',$container->id);
            if($bookingDetails){
            return $bookingDetails->free_time;
            }
        return null;

    }
}