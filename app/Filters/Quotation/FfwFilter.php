<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class FfwFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('ffw_id',$value);
    }
}
