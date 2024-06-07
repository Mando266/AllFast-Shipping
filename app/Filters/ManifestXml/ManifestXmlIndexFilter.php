<?php

namespace App\Filters\ManifestXml;

use App\Filters\AbstractFilter;

class ManifestXmlIndexFilter extends AbstractFilter{
    protected $filters = [
        'ref_no'=>RefNoFilter::class,
        'port_id'=>POLFilter::class,
        'voyage_id'=>VoyageFilter::class,
        'bl_id'=>BlFilter::class,
    ];
}
