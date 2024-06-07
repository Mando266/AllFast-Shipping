<?php

namespace App\Filters\Receipt;
use App\Filters\AbstractBasicFilter;

class ReceiptNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('receipt_no',$value);
    }
}
