<?php

namespace App\Filters\Receipt;
use App\Filters\AbstractBasicFilter;

class BldraftFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('invoice',function($q) use($value){
            $q->where('bldraft_id',$value);
        });
    }
}
