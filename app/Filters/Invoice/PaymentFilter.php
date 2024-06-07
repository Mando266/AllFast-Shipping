<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class PaymentFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('bldraft',function($q) use($value){
            $q->where('payment_kind',$value);
        });
    }
}
