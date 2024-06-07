<?php

namespace App\Filters\Refund;
use App\Filters\AbstractBasicFilter;

class RefundNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('receipt_no',$value);
    }
}
