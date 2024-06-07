<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class InvoiceStatusFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('invoice_status',$value);
    }
}
