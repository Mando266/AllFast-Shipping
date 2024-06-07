<?php
namespace App\Filters\Trucker;

use App\Filters\AbstractBasicFilter;

class NameFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->wherein('company_name',$value);
    }
}
