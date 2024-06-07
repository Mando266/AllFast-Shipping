<?php

namespace App\Exports;

use App\Filters\Quotation\QuotationIndexFilter;
use App\Models\Quotations\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuotationExport implements FromCollection, WithHeadings
{
    protected $quotations;

    /**
     * @param Request $quotations
     */
    public function __construct(Request $quotations)
    {
        $this->quotations = Quotation::filter(new QuotationIndexFilter($quotations))->where('company_id', Auth::user()->company_id)->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            "REF NO",
            "CUSTOMER",
            "Freight Forwarder Name",
            "VALIDITY FROM",
            "VALIDITY TO",
            "EQUIPMENT TYPE",
            "PLACE OF ACCEPTENCE",
            "PLACE OF DELIVERY",
            "LOAD PORT",
            "DISCHARGE PORT",
            "Main Line",
            "OFR",
            "SOC / COC",
            "payment kind",
            "Quotation Type",
            "STATUS",
            "Import Free Time",
            "Export free time",
            "Booking Agency",
            "Payment As Per Agreement",
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {

        $quotations = $this->quotations;
        $exportQuotations = collect();
        foreach ($quotations as $quotation) {
            $tempCollection = collect([
                'REF NO' => $quotation->ref_no,
                "CUSTOMER" => optional($quotation->customer)->name,
                "Freight Forwarder Name" => optional($quotation->ffw)->name,
                "VALIDITY FROM" => $quotation->validity_from,
                "VALIDITY TO" => $quotation->validity_to,
                "EQUIPMENT TYPE" => optional($quotation->equipmentsType)->name,
                "PLACE OF ACCEPTENCE" => optional($quotation->placeOfAcceptence)->name,
                "PLACE OF DELIVERY" => optional($quotation->placeOfDelivery)->name,
                "LOAD PORT" => optional($quotation->loadPort)->name,
                "DISCHARGE PORT" => optional($quotation->dischargePort)->name,
                "Main Line" => optional($quotation->principal)->name,
                "OFR" => $quotation->ofr,
                "SOC / COC" => $quotation->soc == 1 ? "SOC" : "COC",
                "payment kind" => $quotation->payment_kind,
                "Quotation Type" => $quotation->quotation_type,
                "STATUS" => $quotation->status,
                "Import Free Time" => $quotation->import_detention == 0 ? "0" : $quotation->import_detention,
                "Export free time" => $quotation->export_detention == 0 ? "0" : $quotation->export_detention,
                'booking_agency' => optional($quotation->bookingagancy)->name,
                'Payment As Per Agreement' => optional($quotation)->agency_bookingr_ref,

            ]);
            $exportQuotations->add($tempCollection);
        }
        return $exportQuotations;
    }
}
