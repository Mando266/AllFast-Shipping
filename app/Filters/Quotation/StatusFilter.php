<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class StatusFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('status',$value);
    }
}
