<?php

namespace App\Filters\Statements;
use App\Filters\AbstractBasicFilter;

class CustomerFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('id',$value);
    }
}
