<?php
namespace App\Filters\BlDraft;

use App\Filters\AbstractBasicFilter;

class VoyageFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('booking',function($q) use($value){
            $q->where('voyage_id',$value)->orwhere('voyage_id_second',$value);
        });
    }
}
