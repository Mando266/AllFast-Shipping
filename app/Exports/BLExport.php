<?php

namespace App\Exports;

use App\Models\Voyages\VoyagePorts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BLExport implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
        "Booking No",
        "Bl No",
        "Shipper Name",
        "FORWARDER",
        "CONSIGNEE",
        "Notify",
        "Load Port",
        "Final Dest",
        "Line",
        "Vessel",
        "Voyage",
        "LEG",
        "ETA",
        "Shipment Type",
        "Container Type",
        // "Qty",
        "OFR",
        "BL Type",
        "Bl Status",
        ];
    }


    public function collection()
    {
        $bldarfts = session('bldarft');
        $exportbls = collect();

        foreach($bldarfts ?? []  as $bldarft){
            $blstatus = '';
            foreach($bldarft->blDetails as $blDetail){
                    if($bldarft->bl_status == 1){
                        $blstatus = "Confirm";
                    }else{
                        $blstatus = "Draft";
                    }
                    $shipping_status = optional(optional($bldarft->booking)->quotation)->shipment_type;
                    $loadPort = VoyagePorts::where('voyage_id',optional($bldarft->voyage)->id)->where('port_from_name',optional($bldarft->loadPort)->id)->first();
                    $dischargePort = VoyagePorts::where('voyage_id',optional($bldarft->voyage)->id)->where('port_from_name',optional($bldarft->dischargePort)->id)->first();
                    $tempCollection = collect([
                        'Booking No' => optional($bldarft->booking)->ref_no,
                        'Bl No' => $bldarft->ref_no,
                        'Shipper Name' => optional($bldarft->customer)->name,
                        'FORWARDER' => optional($bldarft->booking->forwarder)->name,
                        'CONSIGNEE' => optional($bldarft->customerConsignee)->name,
                        'Notify' => optional($bldarft->customerNotify)->name,
                        'Load Port' => optional($bldarft->loadPort)->code,
                        'Final Dest' => optional($bldarft->dischargePort)->code,
                        'line'=>optional($bldarft->booking->principal)->name,
                        'Vessel' => optional(optional($bldarft->voyage)->vessel)->name,
                        'Voyage' => optional($bldarft->voyage)->voyage_no,
                        'leg' => optional(optional($bldarft->voyage)->leg)->name,
                        'eta' => $shipping_status == "Export"? optional($loadPort)->eta : optional($dischargePort)->eta,
                        'shipping_status' => optional(optional($bldarft->booking)->quotation)->shipment_type,
                        'Container Type' => optional($bldarft->equipmentsType)->name,
                        // 'qty' => "",
                        'OFR' => optional($bldarft->booking->quotation)->ofr,
                        'bl_kind' => $bldarft->bl_kind,
                        'bl_status'=>$blstatus,
                    ]);
            $exportbls->add($tempCollection);

        }
    }
            return $exportbls;
    }
}
