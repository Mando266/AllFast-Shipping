<?php

namespace App\Models\Master;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Lines;
use App\Traits\HasFilter;

use Illuminate\Database\Eloquent\Model;

class SupplierPrice extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'supplier_price';
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
    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type_id','id');
    }
    public function pol(){
        return $this->belongsTo(Ports::class,'pol_id','id');
    }
    public function pod(){
        return $this->belongsTo(Ports::class,'pod_id','id');
    }
    public function line(){
        return $this->belongsTo(Lines::class,'supplier_id','id');
    }
}
