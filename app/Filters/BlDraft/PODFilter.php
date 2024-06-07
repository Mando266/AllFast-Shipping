<?php
namespace App\Filters\BlDraft;

use App\Filters\AbstractBasicFilter;

class PODFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('discharge_port_id',$value);
    }
}
