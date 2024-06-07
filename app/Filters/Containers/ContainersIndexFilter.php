<?php

namespace App\Filters\Containers;

use App\Filters\AbstractFilter;

class ContainersIndexFilter extends AbstractFilter{
    protected $filters = [
        'description'=>DescriptionFilter::class,
        'id'=>IDFilter::class,
        'container_type_id'=>ContainerTypeFilter::class,
        'container_ownership_id'=>ContainerOwnershipFilter::class,
        'is_storge'=>IsStorageFilter::class,
        'code'=>CodeFilter::class,
    ];
}
