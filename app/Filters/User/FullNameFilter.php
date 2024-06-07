<?php
namespace App\Filters\User;

use App\Filters\AbstractBasicFilter;

class FullNameFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('full_name','like',"%{$value}%");
    }
}
