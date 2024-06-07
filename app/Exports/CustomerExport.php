<?php

namespace App\Exports;
use App\Models\Quotations\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "Name",
            "Contact Person",
            "phone",
            "Tax ID",
            "Landline" ,
            "Country",
            "City",
            "Email",
            "Address Line 1",
            "Address Line 2",
            "Sales person",
            "Role" ,
            "Status",
        ];
    }
    

    public function collection()
    {
       
        $customers = session('customers');
        foreach($customers  ?? [] as $customer){
            $customer->country_id = optional($customer->country)->name;
            foreach($customer->CustomerRoles as $customerRole){
                $customer->customer_role_id .= optional($customerRole->role)->name. "  ";
            }
            if($customer->customer_kind == 0){
                $customer->customer_kind = "Primary";
            }else{
                $customer->customer_kind = "Validated";
            }
            unset($customer->id);
        }
        
        return $customers;
    }    
}
