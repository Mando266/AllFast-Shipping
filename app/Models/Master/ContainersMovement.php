<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Support\Facades\Auth;
use App\Models\Containers\Movements;
 
class ContainersMovement extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'containers_movement';
    protected $guarded = []; 

    public function movements()
    {
        return $this->hasMany(Movements::class ,'movement_id','id');
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

    public function containerstock (){
        return $this->belongsto(StockTypes::class,'stock_type_id','id');
    }

    public function containerstatus (){
        return $this->belongsto(ContainerStatus::class,'container_status_id','id');
    }

    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    
    public function scopeUserContainersMovement($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}
