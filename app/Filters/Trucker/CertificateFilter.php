<?php
namespace App\Filters\Trucker;

use App\Filters\AbstractBasicFilter;

class CertificateFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('certificate_type',$value);
    }
}
