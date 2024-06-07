<?php
namespace App\Filters\User;

use App\Filters\AbstractBasicFilter;

class EmployeeNumberFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('employee_no','like',"%{$value}%");
    }
}
