<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class ValidityToFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('validity_to',$value);
    }
}
