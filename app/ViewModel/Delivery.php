<?php
namespace App\ViewModel;

use App\Traits\ToJson;

class Delivery{
    use ToJson;

    public $approach;
    public $packaging;
    public $dateValidity;
    public $exportPort;
    public $countryOfOrigin;
    public $grossWeight;
    public $netWeight;
    public $terms;

    public function __construct($approach="",$packaging="",$dateValidity="",$exportPort="",$countryOfOrigin="EG",$grossWeight=0,$netWeight=0,$terms="")
    {
        $this->packaging = $packaging;
        $this->approach = $approach;
        $this->dateValidity = $dateValidity;
        $this->exportPort = $exportPort;
        $this->countryOfOrigin = $countryOfOrigin;
        $this->grossWeight = $grossWeight;
        $this->netWeight = $netWeight;
        $this->terms = $terms;

    }
}
