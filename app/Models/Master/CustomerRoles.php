<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CustomerRoles extends Model
{
    public $timestamps = false;
    protected $table = 'customer_roles';
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customers::class,'customer_id','id');
    }

    public function role()
    {
        return $this->belongsTo(RoleCustomer::class,'role_id','id');
    }
}
