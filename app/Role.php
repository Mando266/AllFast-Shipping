<?php

namespace App;

use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole implements PermissionSeederContract
{
    use PermissionSeederTrait;
    protected $guarded = [];


    public function users(){
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
