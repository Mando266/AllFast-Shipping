<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class PrincipalFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('principal_name',$value);
    }
}
