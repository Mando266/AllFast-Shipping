<?php
namespace App\ViewModel;

use App\Models\Master\Company;
use App\Traits\ToJson;
use PDO;

class Issuer{
    use ToJson;
    public function __construct()
    {
        // $company = Company::where('branch_id',$invoiceHeader->branch_code)->first();
        // if(is_null($company)){
        //     $company = Company::first();
        // }
        // $address = $company->getTaxAddress();
        // $this->address = $address;
        // $this->address->branchID = $invoiceHeader->branch_code ?? $company->branch_id;
        // $this->type = $company->type;
        // $this->id = $company->tax_id;
        // $this->name = trim($company->tax_company_name);
    }
    public $address = [
        "branchID"=> "0",
        "country"=> "EG",
        "governate"=> "Alexandria",
        "regionCity"=> "Alexandria",
        "street"=> "ش مدحت الملیجى متفرع من ش سوريا قمراية الدور الاول رشدى الرمل, قسم أول الرمل, الاسكندرية",
        "buildingNumber"=> "39",
        "postalCode"=> "",
        "floor"=> "",
        "room"=> "",
        "landmark"=> "",
        "additionalInformation"=> "",
    ];

    public $type = "B";
    public $id = "560161093";
    public $name = "میدل ايست للملاحه";
}
