<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class RemarkesFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('remarkes','like',"%{$value}%");
    }
}
