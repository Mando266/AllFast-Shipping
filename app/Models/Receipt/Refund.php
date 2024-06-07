<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Bank;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Models\Master\Customers;
use App\Traits\HasFilter;
use App\User;

class Refund extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'receipts';
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

    public function Bank(){
        return $this->belongsTo(Bank::class,'bank_id','id');
    }

    public function customer(){
        return $this->belongsTo(Customers::class,'customer_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
