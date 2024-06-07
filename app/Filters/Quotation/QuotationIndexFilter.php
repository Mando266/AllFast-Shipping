<?php

namespace App\Filters\Quotation;

use App\Filters\AbstractFilter;

class QuotationIndexFilter extends AbstractFilter{
    protected $filters = [
        'status'=>StatusFilter::class,
        'ref_no'=>RefNoFilter::class,
        'triff_no'=>TriffPortNoFilter::class,
        'validity_from'=>ValidityFromFilter::class,
        'validity_to'=>ValidityToFilter::class,
        'customer_id'=>CustomerFilter::class,
        'ffw_id'=>FfwFilter::class,
        'customer_consignee_id'=>ConsigneeFilter::class,
        'load_port_id'=>POLFilter::class,
        'discharge_port_id'=>PODFilter::class,
        'voyage_id'=>VoyageFilter::class,
        'voyage_id_second'=>SecondVoyageFilter::class,
        'principal_name'=>PrincipalFilter::class,
        'booking_confirm'=>BookingConfirmFilter::class,
        'container_id'=>ContainerFilter::class,
        'is_transhipment'=>TranshipmentFilter::class,
        'booking_type'=>BookingTypeFilter::class,
        'voyage_id_both'=>BothVoyageFilter::class,
        'quotation_id'=>QuotationFilter::class,
    ];
}
