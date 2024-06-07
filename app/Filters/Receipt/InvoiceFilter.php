<?php

namespace App\Filters\Receipt;
use App\Filters\AbstractBasicFilter;

class InvoiceFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('invoice_id',$value);
    }
}
