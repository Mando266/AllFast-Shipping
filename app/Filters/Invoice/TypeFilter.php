<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class TypeFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('type',$value);
    }
}
