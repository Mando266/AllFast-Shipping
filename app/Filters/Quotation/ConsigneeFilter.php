<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class ConsigneeFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('customer_consignee_id',$value);
    }
}
