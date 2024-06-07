<?php

namespace App\Filters\Statements;
use App\Filters\AbstractFilter;
use App\Filters\Invoice\TypeFilter;

class CustomerStatementIndexFilter extends AbstractFilter{
    protected $filters = [
        'bldraft_id'=>BlDraftFilter::class,
        'id'=>CustomerFilter::class,
        'invoice_no'=>InvoiceNoFilter::class,
        'voyage_id'=>VoyageFilter::class,
    ];
}
