<?php

namespace App\Exports;

use App\Filters\Quotation\QuotationIndexFilter;
use App\Models\Booking\Booking;
use App\Models\Voyages\VoyagePorts;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection, WithHeadings
{
    protected $bookings;

    /**
     * @param Request $filter
     */
    public function __construct(Request $filter)
    {
        $this->bookings = Booking::filter(new QuotationIndexFilter($filter))->orderBy('id', 'desc')
            ->where('booking_confirm', '!=', 2)->where('company_id', Auth::user()->company_id)
            ->with('bookingContainerDetails.containerType', 'voyage.vessel', 'voyage.leg', 'bldraft', 'quotation', 'loadPort',
                'dischargePort', 'transhipmentPort', 'secondvoyage.vessel', 'secondvoyage.leg', 'customer', 'forwarder',
                'consignee', 'principal', 'placeOfAcceptence', 'placeOfDelivery', 'pickUpLocation', 'placeOfReturn')->get();
    }

    public function headings(): array
    {
        return [
            "QUOTATION NO",
            "BOOKING REF NO",
            "SHIPPER",
            "FORWARDER",
            "CONSIGNEE",
            "Frist Vessel",
            "Frist VOYAGE",
            "LEG",
            "ETA",
            "Second Vessel",
            "Second VOYAGE",
            "Second LEG",
            "Second ETA",
            "Shipment Type",
            "Main Line",
            "Vessel Operator",
            "PLACE OF Acceptence",
            "PLACE OF DELIVERY",
            "LOAD PORT",
            "DISCHARGE PORT",
            "Pick Up Location",
            "Place Of Return",
            "Transhipment Port",
            "EQUIPMENT TYPE",
            "QTY",
            "OFR",
            "SOC",
            "IMO",
            "OOG",
            "BOOKING CREATION",
            "BOOKING STATUS",
            "Bldraft Status",
            "Assigned",
            "UnAssigned",
            "BOOKING Type",
            "Payment Kind",
            "Booking Agency",
            "Payment As Per Agreement",
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {

        $bookings = $this->bookings;
        $exportBookings = collect();
        foreach ($bookings ?? [] as $booking) {
            $qty = 0;
            $assigned = 0;
            $unassigned = 0;
            $bldraftStatus = '';
            $bookingStatus = '';
            $shipping_status = '';
            if ($booking->has_bl == 0) {
                $bldraftStatus = 'unissued';
            } elseif (optional($booking->bldraft)->bl_status == 1) {
                $bldraftStatus = 'confirm';
            } elseif (optional($booking->bldraft)->bl_status == 0) {
                $bldraftStatus = 'draft';
            }
            foreach ($booking->bookingContainerDetails as $bookingDetail) {
                $qty += $bookingDetail->qty;
                if ($bookingDetail->qty == 1 && $bookingDetail->container_id == "000") {
                    $unassigned += 1;
                } elseif ($bookingDetail->qty == 1 && $bookingDetail->container_id == null) {
                    $unassigned += 1;
                } elseif ($bookingDetail->qty == 1) {
                    $assigned += 1;
                } else {
                    $unassigned += $bookingDetail->qty;
                }
            }
            if ($booking->bookingContainerDetails->count() > 0) {
                if ($booking->booking_confirm == 1) {
                    $bookingStatus = "Confirm";
                } elseif ($booking->booking_confirm == 2) {
                    $bookingStatus = "Cancelled";
                } else {
                    $bookingStatus = "Draft";
                }

                if ($booking->is_transhipment == 1 && $booking->quotation_id == 0) {
                    $shipping_status = 'Transhipment';
                } elseif ($booking->is_transhipment == 0 && $booking->quotation_id == 0) {
                    $shipping_status = 'Draft';
                } else {
                    $shipping_status = optional($booking->quotation)->shipment_type;
                }

                $loadPort = VoyagePorts::where('voyage_id', optional($booking->voyage)->id)->where('port_from_name', optional($booking->loadPort)->id)->first();
                $dischargePort = VoyagePorts::where('voyage_id', optional($booking->voyage)->id)->where('port_from_name', optional($booking->dischargePort)->id)->first();
                $transhipmentPort = VoyagePorts::where('voyage_id', optional($booking->voyage)->id)->where('port_from_name', optional($booking->transhipmentPort)->id)->first();
                $loadPortSecond = VoyagePorts::where('voyage_id', optional($booking->secondvoyage)->id)->where('port_from_name', optional($booking->loadPort)->id)->first();
                $dischargePortSecond = VoyagePorts::where('voyage_id', optional($booking->secondvoyage)->id)->where('port_from_name', optional($booking->dischargePort)->id)->first();
                $transhipmentPortSecond = VoyagePorts::where('voyage_id', optional($booking->secondvoyage)->id)->where('port_from_name', optional($booking->transhipmentPort)->id)->first();
                $tempCollection = collect([
                    'quotation_ref_no' => optional($booking->quotation)->ref_no,
                    'ref_no' => $booking->ref_no,
                    'customer_name' => optional($booking->customer)->name,
                    'forwarder_name' => optional($booking->forwarder)->name,
                    'consignee_name' => optional($booking->consignee)->name,
                    'first_vessel' => optional($booking->voyage)->vessel->name,
                    'voyage_id' => optional($booking->voyage)->voyage_no,
                    'leg' => optional(optional($booking->voyage)->leg)->name,
                    'eta' => $shipping_status == "Export" ? optional($loadPort)->eta : ($shipping_status == "Import" ? optional($dischargePort)->eta : optional($transhipmentPort)->eta),
                    'second_vessel' => optional(optional($booking->secondvoyage)->vessel)->name,
                    'voyage_id_second' => optional($booking->secondvoyage)->voyage_no,
                    'leg_2' => optional(optional($booking->secondvoyage)->leg)->name,
                    'eta_2' => $shipping_status == "Export" ? optional($loadPortSecond)->eta : ($shipping_status == "Import" ? optional($dischargePortSecond)->eta : optional($transhipmentPortSecond)->eta),
                    'shipping_status' => optional($booking->quotation)->shipment_type,
                    'main_line' => optional($booking->principal)->name,
                    'operator' => optional($booking->operator)->name,
                    'placeOfAcceptence' => optional($booking->placeOfAcceptence)->name,
                    'placeOfDelivery' => optional($booking->placeOfDelivery)->name,
                    'loadPort' => optional($booking->loadPort)->name,
                    'dischargePort' => optional($booking->dischargePort)->name,
                    'pickUpLocation' => optional($booking->pickUpLocation)->name,
                    'placeOfReturn' => optional($booking->placeOfReturn)->name,
                    'transhipmentPort' => optional($booking->transhipmentPort)->name,
                    'containerType' => optional($booking->bookingContainerDetails[0]->containerType)->name,
                    'qty' => $qty,
                    'ofr' => optional($booking->quotation)->ofr,
                    'soc' => optional($booking->quotation)->soc == 1 ? "SOC" : "COC",
                    'imo' => $booking->imo == 1 ? "IMO" : "",
                    'oog' => $booking->oog == 1 ? "OOG" : "",
                    'created_at' => $booking->created_at,
                    'booking_confirm' => $bookingStatus,
                    'bl_status' => $bldraftStatus,
                    'assigned' => $assigned,
                    'unassigned' => $unassigned,
                    'booking_type' => optional(optional($booking)->quotation)->quotation_type ?: optional($booking)->booking_type,
                    'payment_kind' => optional(optional($booking)->quotation)->payment_kind,
                    'booking_agency' => optional(optional($booking->quotation)->bookingagancy)->name,
                    'Payment As Per Agreement' => optional($booking->quotation)->agency_bookingr_ref,
                ]);
                $exportBookings->add($tempCollection);
            }
        }

        return $exportBookings;
    }
}
