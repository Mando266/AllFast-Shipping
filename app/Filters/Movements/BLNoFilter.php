<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class BLNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('bl_no',$value);
    }
}
