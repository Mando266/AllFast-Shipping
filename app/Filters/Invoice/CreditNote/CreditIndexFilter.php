<?php

namespace App\Filters\Invoice\CreditNote;
use App\Filters\AbstractFilter;
use App\Filters\Invoice\TypeFilter;

class CreditIndexFilter extends AbstractFilter{
    protected $filters = [
        'customer_id'=>CustomerFilter::class,
    ];
}
