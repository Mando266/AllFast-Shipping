<?php

namespace App\Filters\Receipt;
use App\Filters\AbstractFilter;
use App\Filters\Invoice\BlDraftFilter;
use App\Filters\Invoice\TypeFilter;

class ReceiptIndexFilter extends AbstractFilter{
    protected $filters = [
        'customer'=>CustomerFilter::class,
        'invoice'=>InvoiceFilter::class,
        'bldraft'=>BlDraftFilter::class,
        'receipt_no'=>ReceiptNoFilter::class,
    ];
}
