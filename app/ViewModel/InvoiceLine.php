<?php
namespace App\ViewModel;

use App\Traits\ToJson;

class InvoiceLine{
    use ToJson;

    public $description;
    public $itemType = "GS1";
    public $itemCode;
    public $unitType = "EA";
    public $quantity;
    public $internalCode;
    public $salesTotal;
    public $valueDifference = 0;
    public $totalTaxableFees = 0;
    public $netTotal;
    public $itemsDiscount;

    public $unitValue;
    public $discount;
    public $taxableItems = [];

    public function __construct($description="",$itemCode="",$quantity=1,$internalCode="",$salesTotal=0,$netTotal=0,$itemsDiscount=0,$total=0)
    {
        $this->description = $description;
        $this->itemCode = $itemCode;
        $this->quantity =  floatval($quantity);
        $this->internalCode = $internalCode;
        $this->salesTotal = floatval($salesTotal);
        $this->netTotal = floatval($netTotal);
        $this->itemsDiscount = floatval($itemsDiscount);
        $this->total = floatval($total);

    }

    public function setUnitValue($amountEGP=0,$amountSold=0,$currencyExchangeRate=1,$currency="EGP"){
        if($currency == "EGP"){
            $this->unitValue = [
                "currencySold"=>$currency,
                "amountEGP"=>floatval($amountEGP),
                "amountSold"=>floatval($amountSold)
            ];
        }else{
            $this->unitValue = [
                "currencySold"=>$currency,
                "amountEGP"=>floatval($amountEGP),
                "amountSold"=>floatval($amountSold),
                "currencyExchangeRate"=>floatval($currencyExchangeRate)
            ];
        }

    }
    public function setDiscount($rate=0,$amount=0){
        $this->discount = [
            "rate"=>intval($rate),
            "amount"=>floatval($amount)
        ];
    }

    public function addTaxItem($amount=0,$taxType="T1",$subType="V009",$rate=14){
        $this->taxableItems[] = [
            "amount"=>floatval($amount),
            "taxType"=>$taxType,
            "subType"=>$subType,
            "rate"=>intval($rate)
        ];
    }

}
