<?php
namespace App\ViewModel;

use App\Traits\ToJson;

class Payment{
    use ToJson;

    public $bankName;
    public $bankAddress;
    public $bankAccountNo;
    public $bankAccountIBAN;
    public $swiftCode;
    public $terms;

    public function __construct($bankName="",$bankAddress="",$bankAccountNo="",$bankAccountIBAN="",$swiftCode="",$terms="")
    {
        $this->bankAddress = $bankAddress;
        $this->bankName = $bankName;
        $this->bankAccountNo = $bankAccountNo;
        $this->bankAccountIBAN = $bankAccountIBAN;
        $this->swiftCode = $swiftCode;
        $this->terms = $terms;

    }
}
