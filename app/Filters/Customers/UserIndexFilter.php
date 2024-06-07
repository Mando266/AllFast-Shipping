<?php

namespace App\Filters\Customers;

use App\Filters\AbstractFilter;

class UserIndexFilter extends AbstractFilter{
    protected $filters = [
        'name'=>UserNameFilter::class,
        'country_id'=>CountryFilter::class,
        'tax_card_no'=>TaxFilter::class,
    ];
}
