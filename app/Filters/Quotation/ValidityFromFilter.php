<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class ValidityFromFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('validity_from',$value);
    }
}
