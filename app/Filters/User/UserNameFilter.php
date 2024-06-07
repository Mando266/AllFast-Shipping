<?php
namespace App\Filters\User;

use App\Filters\AbstractBasicFilter;

class UserNameFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('name','like',"%{$value}%");
    }
}
