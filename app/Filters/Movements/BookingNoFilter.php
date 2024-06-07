<?php
namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class BookingNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('booking_no',$value);
    }
}
