<?php

namespace App\Models\Master;

use App\Traits\HasContactPeople;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Support\Facades\Auth;

class Suppliers extends Model implements PermissionSeederContract
{
    protected $table = 'suppliers';
    protected $guarded = [];

    use HasContactPeople;
    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete'
        ]);
    }

    public function country()
    {
        return $this->belongsto(Country::class, 'country_id', 'id');
    }

    public function company()
    {
        return $this->belongsto(Company::class, 'company_id', 'id');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class);
    }

    public function scopeUserSuppliers($query)
    {
        if (is_null(Auth::user()->company_id)) {
            $query;
        } else {
            $query->where('company_id', Auth::user()->company_id);
        }
    }
}
