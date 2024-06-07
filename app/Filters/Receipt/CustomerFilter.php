<?php

namespace App\Filters\Receipt;
use App\Filters\AbstractBasicFilter;

class CustomerFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('invoice',function($q) use($value){
            $q->where('customer_id',$value);
        });
    }
}
