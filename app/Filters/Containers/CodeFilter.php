<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class CodeFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('code',$value);
    }
}
