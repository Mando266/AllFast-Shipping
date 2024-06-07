<?php
namespace App\ViewModel;

use App\Traits\ToJson;

class Reciver{
    use ToJson;

    public $address;
    public $id;
    public $name;
    public $type;

    public function __construct($address,$id,$name,$type="B")
    {
        $this->address =$address ;
        $this->id =$id ;
        $this->name = trim($name) ;
        $this->type =$type ;
    }
}
