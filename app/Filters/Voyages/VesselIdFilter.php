<?php
namespace App\Filters\Voyages;

use App\Filters\AbstractBasicFilter;

class VesselIdFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('id',$value);
    }
}
