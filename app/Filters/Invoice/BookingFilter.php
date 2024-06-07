<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class BookingFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->orWhere('booking_ref',$value);
    }
}
