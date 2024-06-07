<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class VesselFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('vessel_id',$value);
    }
}
