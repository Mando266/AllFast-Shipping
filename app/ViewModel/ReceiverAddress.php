<?php
namespace App\ViewModel;

use App\Traits\ToJson;

class ReceiverAddress{
    use ToJson;

    public $country;
    public $governate;
    public $regionCity;
    public $street;
    public $buildingNumber;
    public $postalCode;
    public $floor =  "";
    public $room =  "";
    public $landmark= "";
    public $additionalInformation =  "";
    public function __construct($governate,$regionCity,$street,$buildingNumber,$postalCode,$country="EG")
    {
        $this->governate = $governate;
        $this->regionCity = $regionCity;
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->country = $country;
        $this->postalCode = $postalCode ?? "";
    }

}
