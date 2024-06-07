<?php
namespace App\Filters\SupplierPrice;

use App\Filters\AbstractBasicFilter;

class SupplierFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('supplier_id',$value);
    }
}
