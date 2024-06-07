<?php

namespace App\Exports;
use App\Models\Quotations\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VoyageExport implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
            "Vessel Code",
            "Vessel Name",
            "Voyage No",
            "Leg" ,
            "PORT",
            "ETA",
            "ETD",
            "Terminal Name" ,
            "Road NO",
            "BL Engaged",
            "Booking Engaged",
            "Shipment Type",
        ];
    }


    public function collection()
    {

        $voyages = session('voyages');
        $exportVoyages = collect();

        foreach($voyages  ?? [] as $voyage){
            $exportNum = 0;
            $importNum = 0;
            foreach($voyage->bldrafts as $bldraft){
                if(optional(optional($bldraft->booking)->quotation)->shipment_type == "Export"){
                    $exportNum++;
                }else{
                    $importNum++;
                }
            }
            foreach($voyage->voyagePorts as $voyagePort){
                $tempCollection = collect([
                    'code' => optional($voyage->vessel)->code,
                    'name' => optional($voyage->vessel)->name,
                    'voyage_no'=> $voyage->voyage_no,
                    'leg'=> optional($voyage->leg)->name,
                    'port'=> optional($voyagePort->port)->name,
                    'eta'=> $voyagePort->eta,
                    'etd'=> $voyagePort->etd,
                    'terminal'=> optional($voyagePort->terminal)->name,
                    'road_no'=> $voyagePort->road_no,
                    'bl_engaged'=> $voyage->bldrafts->count(),
                    'booking_engaged'=>$voyage->bookings->where('booking_confirm','!=','2')->count() + $voyage->bookingSecondVoyage->where('booking_confirm','!=','2')->count(),
                    'shipment_type'=> $exportNum . ' Export - ' . $importNum . ' Import',
                ]);
                $exportVoyages->add($tempCollection);
            }
        }

        return $exportVoyages;
    }
}
