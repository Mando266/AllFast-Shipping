<?php
namespace App\Filters\Customers;

use App\Filters\AbstractBasicFilter;

class TaxFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('tax_card_no','like',"%{$value}%");
    }
}
