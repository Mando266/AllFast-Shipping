<?php

namespace App\Exports;

use App\Models\Bl\BlDraft;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CalculationExport implements FromCollection, WithHeadings
{

    public function headings(): array
    {
        return [
            "SR",
            "Booking No.",
            "CONTAINER NO",
            "CONTAINER TYPE",
            "DEPOT NAME",
            "GATE IN MOVE CODE",
            "MOVE Date",
            "NEXT MOVE CODE",
            "FREE DAYS",
            "FREE TIME END DATE",
            "STORAGE TILL DATE",
            "CHARGABLE DAYS",
            "AMOUNT",
            "CURRENCY",
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $calculations = session()->pull('calculations');
        if($calculations != null){
            $bl = BlDraft::find($calculations['input']['bl_no']);
            $calculationexport = collect();
            $count = 1;
            $freeTime = $calculations['freetime'];

            foreach ($calculations['calculation']['containers'] as $container) {
                $fromTime = Carbon::parse($container['from']);
                $toTime = Carbon::parse($container['to']);
            
                if ($freeTime == 0) {
                    if (count($container['periods']) != 0) {
                        $freeTime = $container['periods'][0]['days'];
                    }
                }
            
                $totalDays = Carbon::parse($toTime)->diffInDays($fromTime); // total days
                $chargableDays = $totalDays - $freeTime + 1; 
                $freeTimeTillDate = $fromTime->copy()->addDays($freeTime - 1); // Calculate without modifying $fromTime
          //dd($chargableDays);
                $tempCollection = collect([
                    "SR" => $count,
                    "Booking No." => $bl->booking->ref_no,
                    "CONTAINER NO" => $container['container_no'],
                    "CONTAINER TYPE" => $container['container_type'],
                    "DEPOT NAME" => $bl->booking->terminals->code,
                    "GATE IN MOVE CODE" => $container['from_code'],
                    "MOVE DATE" => Carbon::parse($container['from'])->format('Y-m-d'),
                    "NEXT MOVE CODE" => $container['to_code'],
                    "FREE DAYS" => $calculations['freetime'],
                    "FREE TIME END DATE" => $freeTimeTillDate->format('Y-m-d'),
                    "STORAGE TILL DATE" => Carbon::parse($container['to'])->format('Y-m-d'),
                    "CHARGABLE DAYS" => $chargableDays,
                    "AMOUNT" => $container['total'],
                    "CURRENCY" => $calculations['calculation']['currency'],
                ]);
                $calculationexport->add($tempCollection);
                $count++;
            }
    
            $tempCollection = collect([
                "SR" => '',
                "Booking No." => '',
                "CONTAINER NO" => '',
                "CONTAINER TYPE" => '',
                "DEPOT NAME" => '',
                "GATE IN MOVE CODE" => '',
                "MOVE DATE" => '',
                "NEXT MOVE CODE" => '',
                "FREE DAYS" => '',
                "FREE TIME END DATE" => '',
                "STORAGE TILL DATE" => '',
                "CHARGABLE DAYS" => '',
                "AMOUNT" => $calculations['calculation']['grandTotal'],
                "CURRENCY" => '',
            ]);
            $calculationexport->add($tempCollection);
            return $calculationexport;
        }
    }
}
