<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class TranshipmentFilter extends AbstractBasicFilter{
    public function filter($value)
    {

        return $this->builder->Where('is_transhipment',$value);
    }
}
