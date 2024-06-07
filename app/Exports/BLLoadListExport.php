<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Voyages\VoyagePorts;

class BLLoadListExport implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
        "Booking No",
        "Cstar Ref No",
        "Bl No",
        "Shipper Name",
        "FORWARDER",
        "CONSIGNEE",
        "Notify",
        "Load Port",
        "Transhipment Port",
        "Final Dest",
        "Line",
        "Vessel",
        "Voyage",
        "ETA",
        "ETD",
        "H S Code",
        "Container Type",
        "Container No",
        "OFR",
        "Seal No",
        "PACKS",
        "PACKS TYPE",
        "TARE WEIGHT",
        "GROSS WEIGHT KGS",
        "NET WEIGHT KGS	",
        "VGM",
        "MEASURE CBM",
        "Payment TYPE",
        "BL Type",
        "Description",
        ];
    }


    public function collection()
    {
        $bldarfts = session('bldarft');
        $exportbls = collect();

        foreach($bldarfts ?? []  as $bldarft){
            foreach($bldarft->blDetails as $blDetail){
                    if($bldarft->bl_status == 1){
                        $bldarft->bl_status = "Confirm";
                    }else{
                        $bldarft->bl_status = "Draft";
                    }
                    $shipping_status = optional(optional($bldarft->booking)->quotation)->shipment_type;
                    $loadPort = VoyagePorts::where('voyage_id',optional($bldarft->voyage)->id)->where('port_from_name',optional($bldarft->loadPort)->id)->first();
                    $dischargePort = VoyagePorts::where('voyage_id',optional($bldarft->voyage)->id)->where('port_from_name',optional($bldarft->dischargePort)->id)->first();

                    $tempCollection = collect([
                        'Booking No' => optional($bldarft->booking)->ref_no,
                        'Cstar Ref No' => optional($bldarft->booking)->forwarder_ref_no,
                        'Bl No' => $bldarft->ref_no,
                        'Shipper Name' => optional($bldarft->customer)->name,
                        'FORWARDER' => optional($bldarft->booking->forwarder)->name,
                        'CONSIGNEE' => optional($bldarft->customerConsignee)->name,
                        'Notify' => optional($bldarft->customerNotify)->name,
                        'Load Port' => optional($bldarft->loadPort)->code,
                        'Transhipment Port' => optional(optional($bldarft->booking)->transhipmentPort)->name,
                        'Final Dest' => optional($bldarft->dischargePort)->code,
                        'line'=>optional(optional($bldarft->booking)->principal)->name,
                        'Vessel' => optional(optional($bldarft->voyage)->vessel)->name,
                        'Voyage' => optional($bldarft->voyage)->voyage_no,
                        'eta' => $shipping_status == "Export"? optional($loadPort)->eta : optional($dischargePort)->eta,
                        'etd' => $shipping_status == "Export"? optional($loadPort)->etd : optional($dischargePort)->etd,
                        'commodity_code'=> $bldarft->booking->commodity_code,
                        'Container Type' => optional($bldarft->equipmentsType)->name,
                        'Container No' => optional($blDetail->container)->code,
                        'OFR' => optional($bldarft->booking->quotation)->ofr,
                        'Seal No' => $blDetail->seal_no,
                        'PACKS' => $blDetail->packs,
                        'PACKS TYPE' => $blDetail->pack_type,
                        'TARE WEIGHT' => optional($blDetail->container)->tar_weight,
                        'CARGO WEIGHT' => $blDetail->gross_weight,
                        'NET WEIGHT' => $blDetail->net_weight,
                        'vgm'=>(float)optional($blDetail->container)->tar_weight + (float)$blDetail->gross_weight,
                        'MEASURE' => $blDetail->measurement,
                        'Payment' => $bldarft->payment_kind,
                        'bl_kind' => $bldarft->bl_kind,
                        'Description' => $bldarft->descripions,
                    ]);
            $exportbls->add($tempCollection);
        }
    }
        return $exportbls;
    }
}
