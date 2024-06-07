<?php

namespace App\Services;

use App\Models\Booking\Booking;
use App\Models\Containers\Movements;
use App\Models\Master\ContainersMovement;
use App\Models\Master\Country;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\PortCharge;
use App\Models\PortChargeInvoice;
use App\Models\Voyages\Voyages;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class PortChargeInvoiceService
{

    /**
     * @param $invoiceData
     * @return array
     */
    public function extractInvoiceData($invoiceData): array
    {
        Arr::forget($invoiceData, ['_token', 'rows', "vessel_id", 'voyage_id', 'voyage_costs']);
        $invoiceData['selected_costs'] = implode(',', $invoiceData['selected_costs']);
        return $invoiceData;
    }

    /**
     * @param $rawRows
     * @return array
     */
    public function prepareInvoiceRows($rawRows): array
    {
        $rows = $this->separateInputByIndex($rawRows);
        $selectedCosts = request()->selected_costs;
        $identifiers = [
            "port_charge_id",
            "service",
            "bl_no",
            "container_no",
            "is_transhipment",
            "shipment_type",
            "quotation_type",
            "additional_fees_description",
        ];
        $selectedItems = array_merge($selectedCosts, $identifiers);
        $rows->transform(fn($row) => $row->only($selectedItems));
        foreach ($rows as $row) {
            foreach ($selectedCosts as $cost) {
                if (in_array($cost, ["power_days", "storage_days", "pti_type"])){
                    continue;
                }
                foreach ($row[$cost] ?? [] as $k => $v) {
                    $row["{$cost}_currency"] = $k;
                    $row[$cost] = $v;
                }
            }
        }
        return $rows->toArray();
    }


    /**
     * @return array|float|int
     */
    public function calculateStorageCost($daysInPort, $container_size, $portCharge, $isMinus = false)
    {
        $free_days = $portCharge->storage_free;
        $slab1_period = $portCharge->storage_slab1_period;
        $slab1_20ft = $portCharge->storage_slab1_20ft;
        $slab1_40ft = $portCharge->storage_slab1_40ft;
        $slab2_20ft = $portCharge->storage_slab2_20ft;
        $slab2_40ft = $portCharge->storage_slab2_40ft;
        $cost = 0;

        if ($daysInPort > $free_days) {
            if ($daysInPort <= ($free_days + $slab1_period)) {
                $daysInSlab1 = $daysInPort - $free_days;
                $cost += $daysInSlab1 * ($container_size === 20 ? $slab1_20ft : $slab1_40ft);
            } else {
                $daysInSlab1 = $slab1_period;
                $cost += $daysInSlab1 * ($container_size === 20 ? $slab1_20ft : $slab1_40ft);

                $daysInSlab2 = $daysInPort - $free_days - $slab1_period;
                $cost += $daysInSlab2 * ($container_size === 20 ? $slab2_20ft : $slab2_40ft);
            }
        }

        if ($isMinus) {
            return $cost;
        }
        $cost_minus_one = $this->calculateStorageCost($daysInPort - 1, $container_size, $portCharge, true);

        return [$cost, $cost_minus_one];
    }

    /**
     * @param $containerId
     * @param $storage_from
     * @param $storage_to
     * @param $blNo
     * @return int
     */
    public function calculateDays($containerId, $storage_from, $storage_to, $blNo): int
    {
        $bookingId = Booking::where('ref_no', $blNo)->first()->id;

        $fromMovement = Movements::where('container_id', $containerId)
            ->whereHas('movementcode', fn($q) => $q->where('code', $storage_from))
            ->where('booking_no', $bookingId)->first();
        if ($fromMovement) {
            $toMovement = Movements::where('container_id', $containerId)
                ->whereDate('movement_date', '>=', $fromMovement->movement_date)
                ->whereHas('movementcode', fn($q) => $q->where('code', $storage_to))
                ->orderBy('movement_date')
                ->first()
                ??
                Movements::where('container_id', $containerId)
                    ->whereDate('movement_date', '>=', $fromMovement->movement_date)
                    ->where('id', '<>', $fromMovement->id)
                    ->orderBy('movement_date')
                    ->first();
            if ($toMovement) {
                $fromDate = Carbon::parse($fromMovement->movement_date);
                $toDate = Carbon::parse($toMovement->movement_date);

                return $fromDate->diffInDays($toDate) + 1;
            }
        }

        return 0;
    }

    /**
     * @param $data
     * @return Collection
     */
    public function separateInputByIndex($data): Collection
    {
        $details = collect();

        for ($i = 0; $i < count(reset($data)); $i++) {
            $item = collect();

            foreach ($data as $key => $values) {
                $item[$key] = $values[$i] ?? null;
            }

            $details->push($item);
        }

        return $details;
    }

    public function getMovementDate($containerId, $movementCode, $blNo)
    {
        return Movements::where('container_id', $containerId)
            ->whereHas('movementcode', fn($q) => $q->where('code', $movementCode))
            ->where('bl_no', $blNo)->first()->movement_date;
    }

    /**
     * @return array|float|int
     */
    public function calculatePowerCost($daysInPort, $container_size, $portCharge, $isMinus = false)
    {
        $free_days = $portCharge->power_free;
        $day_20ft = $portCharge->power_20ft;
        $day_40ft = $portCharge->power_40ft;

        $cost = 0;
        if ($daysInPort > $free_days) {
            $daysInSlab1 = $daysInPort - $free_days;
            $cost += $daysInSlab1 * ($container_size === 20 ? $day_20ft : $day_40ft);
        }

        if ($isMinus) {
            return $cost;
        }
        $cost_minus_one = $this->calculatePowerCost($daysInPort - 1, $container_size, $portCharge, true);

        return [$cost, $cost_minus_one];
    }

    public function getFormViewData(): array
    {
        $userCompanyId = auth()->user()->company_id;
        $vessels = Vessels::where('company_id', $userCompanyId)->orderBy('id')->get();
        $voyages = Voyages::where('company_id', $userCompanyId)->orderBy('id')->get();
        $lines = Lines::where('company_id', $userCompanyId)->orderBy('id')->get();
        $portCharges = PortCharge::paginate(10);
        $possibleMovements = ContainersMovement::all();
        $countries = Country::orderBy('name')->get();
        $ports = Ports::where('company_id', $userCompanyId)->orderBy('id')->get();
        $costs = PortChargeInvoice::COSTS;

        return compact(
            'vessels',
            'voyages',
            'lines',
            'portCharges',
            'possibleMovements',
            'countries',
            'ports',
            'costs',
        );
    }
}