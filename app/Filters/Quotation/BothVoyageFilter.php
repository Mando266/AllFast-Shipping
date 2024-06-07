<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class BothVoyageFilter extends AbstractBasicFilter{
    // public function filter($value)
    // {
    //     return $this->builder->Where('voyage_id_second',$value)->orwhere('voyage_id',$value);
    // }


    public function filter($value) {
        if (!empty($value)) {
            $this->builder->where(function($query) use ($value) {
                $query->where('voyage_id_second', $value)
                      ->orWhere('voyage_id', $value);
            });
        }
    }
}
