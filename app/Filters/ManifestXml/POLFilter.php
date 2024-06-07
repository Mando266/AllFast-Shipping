<?php
namespace App\Filters\ManifestXml;

use App\Filters\AbstractBasicFilter;

class POLFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('load_port_id',$value);
    }
}
