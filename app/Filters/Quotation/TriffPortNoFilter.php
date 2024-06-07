<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class TriffPortNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('triff_no',$value);
    }
}
