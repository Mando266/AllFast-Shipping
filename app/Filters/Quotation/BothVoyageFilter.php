<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class BothVoyageFilter extends AbstractBasicFilter{
    public function filter($values) {
        if (!empty($values)) {
            $this->builder->where(function($query) use ($values) {
                $query->whereIn('voyage_id_second', $values)
                      ->orWhereIn('voyage_id', $values);
            });
        } 
    }   
}
