<?php

namespace App\Models\Xml;

use App\Models\Bl\BlDraft;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;

class Xml extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'manifest_xml';
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

    public function bldraft(){
        return $this->belongsTo(BlDraft::class,'bl_id','id');
    }

    public function voyage(){
        return $this->belongsTo(Voyages::class,'voyage_id','id');
    }

    public function port(){
        return $this->belongsTo(Ports::class,'port_id','id');
    }
}
