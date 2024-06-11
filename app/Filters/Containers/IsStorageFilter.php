<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class IsStorageFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('is_storge',$value);
    }
}
