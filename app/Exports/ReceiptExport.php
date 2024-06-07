<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReceiptExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "Receipt No",
            "Invoice No",
            "Bl No",
            "Customer Name" ,
            "Payment Methods",
            "Total",
            "Paid",
            "Currancy",
            "User" ,
        ];
    }
    

    public function collection()
    {
       
        $receipts = session('receipts');
        $receiptexport = collect();
        foreach($receipts  ?? [] as $receipt){
            $PaymentMethods = '';
            $Curency = '';
                if(optional($receipt->invoice)->add_egp == 'false'){
                    $Curency = 'USD';
                }elseif(optional($receipt->invoice)->add_egp == 'onlyegp'){
                    $Curency = 'EGP';
                }

            if($receipt->bank_transfer != null){
                $PaymentMethods = 'Bank Transfer';
            }

            if($receipt->bank_deposit != null){
                $PaymentMethods = 'Bank Deposit'; 
            }

            if($receipt->bank_check != null){
                $PaymentMethods = 'Bank Check'; 
            }

            if($receipt->bank_cash != null){
                $PaymentMethods = 'Cash'; 
            }
            if($receipt->matching != null){
                $PaymentMethods = 'Matching'; 
            }
            
                $tempCollection = collect([
                    'Receipt No' => $receipt->receipt_no,
                    'Invoice No' => optional($receipt->invoice)->invoice_no,
                    'Bl No'=> optional($receipt->bldraft)->ref_no,
                    'Customer Name'=> optional(optional($receipt->invoice)->customerShipperOrFfw)->name,
                    'Payment Methods'=> $PaymentMethods,
                    'Total'=> $receipt->total,
                    'Paid'=> $receipt->paid,
                    'currancy'=>$Curency,
                    'User'=> optional($receipt->user)->name,
                ]);
                $receiptexport->add($tempCollection);
        }
        
        return $receiptexport;
    }    
}
