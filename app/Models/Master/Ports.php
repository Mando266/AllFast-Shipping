<?php

namespace App\Models\Master;

use App\Models\Voyages\VoyagePorts;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Support\Facades\Auth;

class Ports extends Model implements PermissionSeederContract
{
    protected $table = 'ports';
    protected $guarded = [];
    use HasFilter;

    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete' 
        ]);
    }
    public function voyagePorts()
    {
        return $this->hasMany(VoyagePorts::class ,'port_from_name','id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function PortTypes(){
        return $this->belongsTo(PortTypes::class,'port_type_id','id');
    }
    // public function company (){
    //     return $this->belongsto(Company::class,'company_id','id');
    // }
    public function Agent (){
        return $this->belongsto(Agents::class,'agent_id','id');
    }
    public function Terminal (){
        return $this->belongsto(Terminals::class,'terminal_id','id');
    }
    // public function scopeUserPorts($query){
    //     if(is_null(Auth::user()->company_id))
    //     {
    //         $query;
    //     }else{
    //         $query->where('company_id',Auth::user()->company_id);
    //     }
    
    // }
}
