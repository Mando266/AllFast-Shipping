<?php

namespace Bitwise\PermissionSeeder\Traits;

trait PermissionSeederTrait{
    public function getPermissionDisplayName(){
        return basename(str_replace('\\', '/', static::class));
    }

    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit'
        ]);
    }

}
