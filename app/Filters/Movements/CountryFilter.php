<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class CountryFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('country_id',$value);
    }
}
