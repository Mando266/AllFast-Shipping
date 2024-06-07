<?php

namespace App\Exports;
use App\Models\Quotations\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocalPortTriffShowExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "CHARGE TYPE",
            "EQUIPMENT TYPE",
            "UNIT",
            "CURRENCY" ,
            "SELLING PRICE",
            "COST",
            "AGENCY REVENE",
            "LINER",
            "PAYER" ,
            "IMPORT OR EXPORT",
            "TRIFF NO",
        ];
    }
    

    public function collection()
    {
       
        $TriffNo = session('TriffNo');
        $triffPriceDetailes = session('triffPriceDetailes');
        // dd($triffPriceDetailes);
        $exportTriffs = collect();
 
        foreach($triffPriceDetailes as $triffPriceDetail){
            if($triffPriceDetail->is_import_or_export == 0)
            $importExport = "Import";
        elseif($triffPriceDetail->is_import_or_export == 1)
            $importExport = "Export";
        elseif($triffPriceDetail->is_import_or_export == 2)
            $importExport = "Empty";
        elseif($triffPriceDetail->is_import_or_export == 3)
            $importExport = "Transshipment";
        else
            $importExport = "Empty";
        
                $tempCollection = collect([
                    'charge_type' => $triffPriceDetail->charge_type,
                    'equipment_type_id' => $triffPriceDetail->equipment_type_id == 100 ? "All" : optional($triffPriceDetail->equipmentsType)->name,
                    'unit' => $triffPriceDetail->unit, 	
                    'currency' => $triffPriceDetail->currency, 	
                    'selling_price' => $triffPriceDetail->selling_price,
                    'cost' => $triffPriceDetail->cost, 	
                    'agency_revene' => $triffPriceDetail->agency_revene, 	
                    'liner' => $triffPriceDetail->liner, 	
                    'payer' => $triffPriceDetail->payer, 	
                    'importExport' => $importExport,
                    'triffno' => $TriffNo->triff_no,
                    
                ]);
                $exportTriffs->add($tempCollection);
        }
        
        return $exportTriffs;
    }    
}
