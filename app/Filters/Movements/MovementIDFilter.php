<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class MovementIDFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereIn('movement_id', (array)$value);
    }
}
