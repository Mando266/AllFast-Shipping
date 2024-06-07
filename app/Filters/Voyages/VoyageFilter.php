<?php
namespace App\Filters\Voyages;

use App\Filters\AbstractBasicFilter;

class VoyageFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('voyage_no','like',"%{$value}%");
    }
}
