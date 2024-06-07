<?php

namespace App\Models\Master;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class Country extends Model
{
    protected $table = 'countries';
    protected $guarded = [];
}
