<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class DescriptionFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('description','like',"%{$value}%");
    }
}
