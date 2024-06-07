<?php
namespace App\Filters\Voyages;

use App\Filters\AbstractBasicFilter;

class PortsFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('port_id',$value);
    }
}
