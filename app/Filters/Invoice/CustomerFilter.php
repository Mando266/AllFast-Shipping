<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class CustomerFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('customer_id',$value);
    }
}
