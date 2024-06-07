<?php

namespace App\Filters\Refund;
use App\Filters\AbstractFilter;

class RefundIndexFilter extends AbstractFilter{
    protected $filters = [
        'customer_id'=>CustomerFilter::class,
        'receipt_no'=>RefundNoFilter::class,
    ];
}
