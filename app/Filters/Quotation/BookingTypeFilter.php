<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class BookingTypeFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('quotation',function($q) use($value){
            $q->where('shipment_type',$value);
        });
    }
}
