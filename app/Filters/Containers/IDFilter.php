<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class IDFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('id',$value);
    }
}
