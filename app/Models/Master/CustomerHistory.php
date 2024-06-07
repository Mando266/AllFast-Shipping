<?php

namespace App\Models\Master;

use App\Models\Receipt\Receipt;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use App\Traits\HasFilter;
use App\User;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class CustomerHistory extends Model
{
    //
    use HasFilter;
    protected $table = 'customers_history';
    protected $guarded = [];
    
    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete' 
        ]);
    }

    public function receipt(){
        return $this->belongsTo(Receipt::class,'receipt_id','id');
    }

    public function user(){
        return $this->belongsTo(Customers::class,'user_id','id');
    }
}
