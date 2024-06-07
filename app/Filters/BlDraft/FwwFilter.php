<?php
namespace App\Filters\BlDraft;

use App\Filters\AbstractBasicFilter;

class FwwFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('booking',function($q) use($value){
            $q->where('ffw_id',$value);
        });
    }
}
