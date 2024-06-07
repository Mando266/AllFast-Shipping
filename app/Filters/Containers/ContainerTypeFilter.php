<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class ContainerTypeFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('container_type_id',$value);
    }
}
