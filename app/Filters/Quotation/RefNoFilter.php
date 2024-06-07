<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class RefNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('ref_no',$value);
    }
}
