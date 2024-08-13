<?php
namespace App\Filters\Invoice;

use App\Filters\AbstractBasicFilter;

class InvoiceItemsFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        // Ensure $value is an array
        if (!is_array($value)) {
            $value = [$value];
        }
        
        return $this->builder->whereHas('chargeDesc', function($q) use ($value) {
            $q->whereIn('charge_description', $value);
        });
    }
}
