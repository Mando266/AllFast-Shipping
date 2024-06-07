<?php

namespace App\Filters\Trucker;

use App\Filters\AbstractFilter;

class TruckerIndexFilter extends AbstractFilter{
    protected $filters = [
 
        'booking_id'=>BookingNoFilter::class,
        'certificate_type'=>CertificateFilter::class,
        'company_name'=>NameFilter::class,

    ];
}
