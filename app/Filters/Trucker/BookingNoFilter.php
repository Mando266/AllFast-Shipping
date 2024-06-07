<?php
namespace App\Filters\Trucker;

use App\Filters\AbstractBasicFilter;

class BookingNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('booking_id',$value);
    }
}
