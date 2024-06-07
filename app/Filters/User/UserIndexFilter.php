<?php

namespace App\Filters\User;

use App\Filters\AbstractFilter;

class UserIndexFilter extends AbstractFilter{
    protected $filters = [
        'name'=>UserNameFilter::class,
        'full_name'=>FullNameFilter::class,
        'company'=>CompanyFilter::class,
        'status'=>ActiveFilter::class,
        'role'=>RoleFilter::class,
        'country_id'=>CountryFilter::class
    ];
}
