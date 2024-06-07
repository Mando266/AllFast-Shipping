<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class QuotationFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('quotation_id',$value);
    }
}
