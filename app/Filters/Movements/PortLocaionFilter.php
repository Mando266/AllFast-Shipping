<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class PortLocaionFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereIn('port_location_id',$value);
    }
}
