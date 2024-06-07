<?php
namespace App\Filters\SupplierPrice;

use App\Filters\AbstractBasicFilter;

class PODFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('pod_id',$value);
    }
}
