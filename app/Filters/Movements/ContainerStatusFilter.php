<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class ContainerStatusFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('container_status',$value);
    }
}
