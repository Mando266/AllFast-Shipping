<?php

namespace App\Filters\SupplierPrice;

use App\Filters\AbstractFilter;

class SupplierPriceIndexFilter extends AbstractFilter{
    protected $filters = [
        'supplier_id'=>SupplierFilter::class,
        'pol_id'=>POLFilter::class,
        'pod_id'=>PODFilter::class,
        'validity_from'=>ValidityFromFilter::class,
        'validity_to'=>ValidityToFilter::class,
    ];
}
