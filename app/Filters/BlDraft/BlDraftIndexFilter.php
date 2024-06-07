<?php

namespace App\Filters\BlDraft;

use App\Filters\AbstractFilter;

class BlDraftIndexFilter extends AbstractFilter{
    protected $filters = [
        'ref_no'=>RefNoFilter::class,
        'customer_id'=>CustomerFilter::class,
        'load_port_id'=>POLFilter::class,
        'discharge_port_id'=>PODFilter::class,
        'voyage_id_both'=>VoyageFilter::class,
        'ffw_id'=>FwwFilter::class,
    ];
}
