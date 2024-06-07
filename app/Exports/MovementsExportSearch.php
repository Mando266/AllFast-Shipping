<?php

namespace App\Exports;

use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainerStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Vessels;
use App\Models\Voyages\Voyages;
use App\Models\Master\Ports;
use App\Models\Booking\Booking;

class MovementsExportSearch implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        $headings = [
            "company_id",
            "container_id",
            "container_type_id",
            "movement_id",
            "movement_date" ,
            "port_location_id",
            "pol_id",
            "pod_id",
            "vessel_id" ,
            "voyage_id",
            "terminal_id",
            "booking_no",
            "bl_no",
            "remarkes",
            "created_at",
            "updated_at",
            "transshipment_port_id",
            "booking_agent_id",
            "free_time",
            "container_status",
            "import_agent",
            "free_time_origin",
            "Lessor/Seller Refrence",
            "Containers Ownership",
            "SOC/COC",
        ];
        if (auth()->user()->lessor_id != 0) {
            // Remove the headings for lessor-specific fields
            $headings = array_diff($headings, [
                "company_id",
                "terminal_id",
                "booking_agent_id",
                "remarkes",
                "created_at",
                "updated_at",
                "import_agent",
                "free_time_origin",
                "Lessor/Seller Refrence",
            ]);
        }
        return $headings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $movements = session('items');

        if (is_array($movements) || is_object($movements))
            {

                foreach($movements as $movement){
                    unset($movement['id']);
                    $movement->container_id = Containers::where('id',$movement->container_id)->pluck('code')->first();
                    $movement->movement_id = ContainersMovement::where('id',$movement->movement_id)->pluck('code')->first();
                    $movement->container_type_id = optional(optional($movement->container)->containersTypes)->name;
                    $movement->container_status = ContainerStatus::where('id',$movement->container_status)->pluck('name')->first();
                    $movement->vessel_id = Vessels::where('id',$movement->vessel_id)->pluck('name')->first();
                    $movement->voyage_id = Voyages::where('id',$movement->voyage_id)->pluck('voyage_no')->first();
                    $movement->booking_agent_id = Agents::where('id',$movement->booking_agent_id)->pluck('name')->first();
                    $movement->import_agent = Agents::where('id',$movement->import_agent)->pluck('name')->first();
                    $movement->description = optional(optional($movement->container)->seller)->name;
                    $movement->containersOwner = optional(optional($movement->container)->containersOwner)->name;
                    $movement->pol_id = Ports::where('id',$movement->pol_id)->pluck('code')->first();
                    $movement->pod_id = Ports::where('id',$movement->pod_id)->pluck('code')->first();
                    $movement->port_location_id = Ports::where('id',$movement->port_location_id)->pluck('code')->first();
                    $movement->booking_no = Booking::where('id',$movement->booking_no)->pluck('ref_no')->first();
                    $movement->SOC_COC = optional($movement->container)->SOC_COC;
                    if (auth()->user()->lessor_id != 0) {
                        unset($movement['id']);
                        unset($movement['company_id']);
                        unset($movement['terminal_id']);
                        unset($movement['created_at']);
                        unset($movement['updated_at']);
                        unset($movement['booking_agent_id']);
                        unset($movement['remarkes']);
                        unset($movement['import_agent']);
                        unset($movement['free_time_origin']);
                        unset($movement['description']);
                    }
                    //dd($movement->container->containersOwner->name);
                }
        return $movements;
    }
}
}
