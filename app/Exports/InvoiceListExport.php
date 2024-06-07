<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Voyages\VoyagePorts;

class InvoiceListExport implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
            "Invoice No",
            "Customer",
            "TAX NO",
            "BL NO",
            "Voyage",
            "Vessel",
            "ETA",
            "ETD",
            "Date Creation",
            "INVOICE TYPE",
            "PAYMENT KIND",
            "TOTAL USD",
            "TOTAL EGP",
            "Invoice Curency",
            "INVOICE STATUS",
            "EQUIPMENT TYPE",
            "Container Type",
            "Booking Type",
            "Shipment STATUS",
            "Payment STATUS",
            "Receipts",
        ];
    }


    public function collection()
    {

        $invoices = session('invoice');
        $exportinvoices = collect();

        foreach($invoices  ?? [] as $invoice){
            $Curency = '';
            $Payment = '';

            if($invoice->bldraft_id == 0){
                $qty = $invoice->qty;
                if($invoice->booking != null){
                    $VoyagePort = VoyagePorts::where('voyage_id',optional($invoice->booking)->voyage_id)
                    ->where('port_from_name',optional($invoice->booking->loadPort)->id)->first();
                }else{
                    $VoyagePort = null;
                }
            }else{
                $VoyagePort = VoyagePorts::where('voyage_id',optional($invoice->bldraft->booking)->voyage_id)
                ->where('port_from_name',optional($invoice->bldraft->booking->loadPort)->id)->first();
            }

        if($invoice->invoice_status == 'confirm'){
            if($invoice->add_egp == 'false'){
                $Curency = 'USD';
            }elseif($invoice->add_egp == 'onlyegp'){
                $Curency = 'EGP';
            }
        }
        if($invoice->paymentstauts == '1'){
            $Payment = 'Paid';
        }elseif($invoice->receipts->count() != 0){
            $Payment = 'Partially Paid';
        }else{
            $Payment = 'UnPaid';
        }
        $receipts = ''; 
        if($invoice->receipts->count() != 0){
            foreach($invoice->receipts as $receipt){
                $receipts .= $receipt->receipt_no . "\n";
            }
        }
    $vat = $invoice->vat;
    $vat = $vat / 100;
    $total = 0;
    $total_eg = 0;
    $total_after_vat = 0;
    $total_before_vat = 0;
    $total_eg_after_vat = 0;
    $total_eg_before_vat = 0;
    $totalAftereTax = 0;
    $totalAftereTax_eg = 0;

    foreach($invoice->chargeDesc as $chargeDesc){
        $total += $chargeDesc->total_amount;
        $total_eg += $chargeDesc->total_egy;

        $totalAftereTax = (($total * $invoice->tax_discount)/100);
        $totalAftereTax_eg = (($total_eg * $invoice->tax_discount)/100);

        if($chargeDesc->add_vat == 1){
                $total_after_vat += ($vat * $chargeDesc->total_amount);
                $total_eg_after_vat += ($vat * $chargeDesc->total_egy);
            }
        }
        $total_before_vat = $total;
        if($total_after_vat != 0){
            $total = $total + $total_after_vat;
        }
        if($total_eg_after_vat != 0){
            $total_eg = $total_eg + $total_eg_after_vat;
        }

                $tempCollection = collect([
                    'invoice_no' => $invoice->invoice_no,
                    'customer' => $invoice->customer,
                    'tax no' => optional($invoice->customerShipperOrFfw)->tax_card_no,
                    'bl no' => $invoice->bldraft_id == 0 ? optional($invoice->booking)->ref_no : optional($invoice->bldraft)->ref_no,
                    'voyage' => $invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no,
                    'vessel' => $invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name,
                    'eta' => optional($VoyagePort)->eta,
                    'etd' => optional($VoyagePort)->etd,
                    'date' => $invoice->created_at->format('Y-m-d'),
                    'type' => $invoice->type,
                    'payment_kind' => optional($invoice->bldraft)->payment_kind,
                    'total usd' => $total,
                    'total egp' => $total_eg,
                    'Curency' =>$Curency,
                    'STATUS' => $invoice->invoice_status,
                    'qty' => $invoice->qty,
                    'Container Type'=> $invoice->bldraft_id == 0 ? optional(optional($invoice->booking)->equipmentsType)->name : optional(optional($invoice->blDraft)->equipmentsType)->name,
                    'Booking Type'=>$invoice->bldraft_id == 0 ? optional($invoice->booking)->shipment_type : optional(optional($invoice->bldraft)->booking)->shipment_type,
                    'ShipmentType' =>$invoice->bldraft_id == 0 ? optional(optional($invoice->booking)->quotation)->quotation_type : optional(optional(optional($invoice->bldraft)->booking)->quotation)->quotation_type,
                    'PaymentSTATUS' => $Payment,
                    'receipts' => $receipts,
                ]);

                $exportinvoices->add($tempCollection);
        }

        return $exportinvoices;
    }
}
