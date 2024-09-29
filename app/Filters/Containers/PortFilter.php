<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class PortFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('port_id',$value);
    }
}
