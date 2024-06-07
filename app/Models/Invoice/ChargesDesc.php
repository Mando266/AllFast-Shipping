<?php

namespace App\Models\Invoice;

use App\Traits\HasFilter;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChargesDesc extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'charges_des';
    protected $guarded = [];

    public function invoices()
    {
        return $this->hasMany(InvoiceChargeDesc::class ,'invoice_id','id');
    }
    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete' 
        ]);
    }

}
