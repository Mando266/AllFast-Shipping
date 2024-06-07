<?php

namespace App\Exports;

use App\Models\Booking\Booking;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoadListFirstOnlyExport implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
        "S.No",
        "Booking No",
        "Frist Vessel",
        "Frist VOYAGE",
        "LEG",
        "Load Port",
        "Final Dest",
        "Transhipment Port",
        "Line",
        "Pick Up Location",
        "Place Of Return",
        "Container No",
        "Container Type",
        "Seal No",
        "TARE WEIGHT",
        "CARGO WEIGHT",
        "VGM",
        "TEMP / VENT / HUMIDTY",
        "Shipper",
        "Consignee",
        "Freight Forwarder",
        "Description",
        "BOOKING STATUS",
        ];
    }


    public function collection()
    {
        $bookings = session('bookings');
        $exportBookings = collect();
        $count = 0;
        foreach($bookings ?? []  as $booking){
            foreach($booking->bookingContainerDetails as $bookingContainerDetail){
                if($booking->bookingContainerDetails->count() > 0){
                    if($booking->booking_confirm == 1){
                        $booking->booking_confirm = "Confirm";
                    }elseif($booking->booking_confirm == 3){
                        $booking->booking_confirm = "Draft";
                    }
                if($bookingContainerDetail->qty == 1){
                    $count ++;
                    if(optional($bookingContainerDetail->container)->code == null){
                        $containerNo = "Dummy";
                    }else{
                        $containerNo = optional($bookingContainerDetail->container)->code;
                    }

                    $tempCollection = collect([
                        'no' => $count,
                        'ref_no' => $booking->ref_no,
                        'first_vessel' => ( request()->input('first_voyage_id') == $booking->voyage->id ) ? ( optional(optional($booking->voyage)->vessel)->name ) : ( optional(optional($booking->secondvoyage)->vessel)->name ) ,
                        'voyage_id' => ( request()->input('first_voyage_id') == $booking->voyage->id ) ? ( optional($booking->voyage)->voyage_no ) : ( optional($booking->secondvoyage)->voyage_no ),
                        'leg' => ( request()->input('first_voyage_id') == $booking->voyage->id ) ? ( optional(optional($booking->voyage)->leg)->name ) : ( optional(optional($booking->secondvoyage)->leg)->name ),
                        'Load Port' => optional($booking->loadPort)->code,
                        'Final Dest' => optional($booking->dischargePort)->code,
                        'transhipmentPort' =>optional($booking->transhipmentPort)->name,
                        'line' => optional($booking->principal)->code,
                        'pickUpLocation' =>optional($booking->pickUpLocation)->name,
                        'placeOfReturn' =>optional($booking->placeOfReturn)->name,
                        'Container No' => $containerNo,
                        'Container Type' => optional($bookingContainerDetail->containerType)->name,
                        'Seal No' => optional($bookingContainerDetail)->seal_no,
                        'TARE WEIGHT' => optional($bookingContainerDetail->container)->tar_weight,
                        'CARGO WEIGHT' => optional($bookingContainerDetail)->weight,
                        'VGM' => (float)optional($bookingContainerDetail)->weight + (float)optional($bookingContainerDetail->container)->tar_weight,
                        'TEMP' => optional($bookingContainerDetail)->haz,
                        'Shipper' => optional($booking->customer)->name,
                        'Consignee' => optional($booking->consignee)->name,
                        'Forwarder' => optional($booking->forwarder)->name,
                        'Description' => optional($booking)->commodity_description,
                        'booking_confirm' => $booking->booking_confirm,
                    ]);
                    $exportBookings->add($tempCollection);

                }elseif($bookingContainerDetail->container == 000 || $bookingContainerDetail->container == Null){
                    $cargoWeight = optional($bookingContainerDetail)->weight / optional($bookingContainerDetail)->qty;
                    for($i=0 ; $i < $bookingContainerDetail->qty ; $i++){
                        $count ++;
                        $tempCollection = collect([
                            'no' => $count,
                            'ref_no' => $booking->ref_no,
                            'first_vessel' => ( request()->input('first_voyage_id') == $booking->voyage->id ) ? ( optional($booking->voyage)->vessel->name ) : ( optional(optional($booking->secondvoyage)->vessel)->name ) ,
                            'voyage_id' => ( request()->input('first_voyage_id') == $booking->voyage->id ) ? ( optional($booking->voyage)->voyage_no ) : ( optional($booking->secondvoyage)->voyage_no ),
                            'leg' => ( request()->input('first_voyage_id') == $booking->voyage->id ) ? ( optional(optional($booking->voyage)->leg)->name ) : ( optional(optional($booking->secondvoyage)->leg)->name ),
                            'Load Port' => optional($booking->loadPort)->code,
                            'Final Dest' => optional($booking->dischargePort)->code,
                            'transhipmentPort' =>optional($booking->transhipmentPort)->name,
                            'line' => optional($booking->principal)->code,
                            'pickUpLocation' =>optional($booking->pickUpLocation)->name,
                            'placeOfReturn' =>optional($booking->placeOfReturn)->name,
                            'Container No' => "Dummy",
                            'Container Type' => optional($bookingContainerDetail->containerType)->name,
                            'Seal No' => optional($bookingContainerDetail)->seal_no,
                            'TARE WEIGHT' => optional($bookingContainerDetail->container)->tar_weight,
                            'CARGO WEIGHT' => $cargoWeight,
                            'VGM' => (float)$cargoWeight + (float)optional($bookingContainerDetail->container)->tar_weight,
                            'TEMP' => optional($bookingContainerDetail)->haz,
                            'Shipper' => optional($booking->customer)->name,
                            'Consignee' => optional($booking->consignee)->name,
                            'Forwarder' => optional($booking->forwarder)->name,
                            'Description' => optional($booking)->commodity_description,
                            'booking_confirm' => $booking->booking_confirm,
                        ]);
                        $exportBookings->add($tempCollection);
                    }

                }
            }
        }
    }
            return $exportBookings;
    }
}
