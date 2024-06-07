<?php

namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class LessorFilter extends AbstractBasicFilter
{
    public function filter($value)
    {
        return $this->builder->whereHas('container',function($q) use($value){
            $q->where('description',$value);
        });
    }
}
