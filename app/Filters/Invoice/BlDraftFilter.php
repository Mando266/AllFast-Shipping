<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class BlDraftFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->orWhere('bldraft_id',$value);
    }
}
