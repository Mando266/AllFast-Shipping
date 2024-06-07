<?php

namespace App\Filters\Statements;
use App\Filters\AbstractFilter;
use App\Filters\Invoice\TypeFilter;

class CustomerStatementRefundIndexFilter extends AbstractFilter{
    protected $filters = [
        'customer_id'=>CustomerFilter::class,
    ];
}
