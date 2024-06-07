<?php

namespace App;

use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission implements PermissionSeederContract
{
    use PermissionSeederTrait;
}
