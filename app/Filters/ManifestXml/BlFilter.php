<?php
namespace App\Filters\ManifestXml;

use App\Filters\AbstractBasicFilter;

class BlFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('voyage_id',$value);
    }
}
