<?php
namespace App\Filters\SupplierPrice;

use App\Filters\AbstractBasicFilter;

class POLFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('pol',$value);
    }
}
