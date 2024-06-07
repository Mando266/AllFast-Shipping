<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TruckerGateExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "BOOKING REF NO",
            "Certificate Type",
            "Shipper Name",
            "Vessel",
            "voyage",
            "Truker Name",
            "Beneficiary Name",
            "Valid To",
            "Issue Date",
            "Inception Date",
            "Payment Date",
            "No Of Containers",
            "Container Type/Size",
            "Gross Permium",
            "Net Contribution",
            "Branch Name",
            "Shippment Typ",
            "Operator",
            "Release Location",
            "BOOKING STATUS",
        ];
    }
    

    public function collection()
    {
        
        $truckergates = session('truckergates');
        $exportTruckergates = collect();
        foreach($truckergates ?? [] as $truckergate){
            $qty = 0;
            $bookingStatus = '';

            foreach($truckergate->booking->bookingContainerDetails as $bookingDetail){
                $qty += $bookingDetail->qty;
            }
            if($truckergate->shipment == 1){
                $truckergate->shipment = "Export";
            }elseif($truckergate->shipment == 2){
                $truckergate->shipment = "Import";
            }else{
                $truckergate->shipment = "Empty Move";
            }

            if($truckergate->booking->booking_confirm == 1){
                $bookingStatus = "Confirm";
            }elseif($truckergate->booking->booking_confirm == 2){
                $bookingStatus = "Cancelled";
            }else{
                $bookingStatus = "Draft";
            }

                $tempCollection = collect([
                    'ref_no' => optional($truckergate->booking)->ref_no,
                    'certificate_type' =>$truckergate->certificate_type,
                    'Shipper_name' => optional($truckergate->booking)->shipment_type == "Export" ? optional($truckergate->booking->customer)->name : optional($truckergate->booking->consignee)->name,
                    'vessel' => optional($truckergate->booking->voyage->vessel)->nam,
                    'voyage' => optional($truckergate->booking->voyage)->voyage_no,
                    'Truker_Name'=>optional($truckergate->trucker)->company_name,
                    'Beneficiary Name'=>$truckergate->beneficiry_name,
                    'valid_to'=>$truckergate->valid_to,
                    'issue_date'=>$truckergate->issue_date,
                    'inception_date'=>$truckergate->inception_date,
                    'payment_date'=>$truckergate->payment_date,
                    'qty' => $truckergate->qty,
                    'Container_Size'=>optional($truckergate->booking->bookingContainerDetails[0]->containerType)->name,
                    'Gross_Permium'=>$truckergate->gross_premium * $truckergate->qty,
                    'Net_Contribution'=>$truckergate->net_contribution * $truckergate->qty,
                    'Branch Name'=>optional($truckergate->booking->loadPort)->name,
                    'Shippment'=>$truckergate->shipment,
                    'Operator'=>$truckergate->operator,
                    'Release Location'=>optional($truckergate->booking->pickUpLocation)->name,
                    'booking Stauts' => $bookingStatus,
                ]);
                $exportTruckergates->add($tempCollection);
        }
        return $exportTruckergates;
    }    
}
