<?php
namespace App\Filters\Customers;

use App\Filters\AbstractBasicFilter;

class UserNameFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->wherein('name',$value);
    }
}
