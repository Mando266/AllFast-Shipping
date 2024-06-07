<?php
namespace App\Filters\User;

use App\Filters\AbstractBasicFilter;

class CountryFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('country_id',$value);
    }
}
