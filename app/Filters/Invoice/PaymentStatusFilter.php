<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class PaymentStatusFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('paymentstauts',$value);
    }
}
