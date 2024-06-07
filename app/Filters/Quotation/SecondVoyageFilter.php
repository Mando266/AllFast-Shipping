<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class SecondVoyageFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('voyage_id_second',$value);
    }
}
