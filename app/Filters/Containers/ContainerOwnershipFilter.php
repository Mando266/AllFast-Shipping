<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class ContainerOwnershipFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('container_ownership_id',$value);
    }
}
