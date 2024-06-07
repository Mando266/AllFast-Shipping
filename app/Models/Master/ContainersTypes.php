<?php

namespace App\Models\Master;
use Illuminate\Support\Facades\Auth;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class ContainersTypes extends Model implements PermissionSeederContract
{
    protected $table = 'containers_type';
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

    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    
    public function containers (){
        return $this->hasMany(Containers::class,'container_type_id','id');
    }

    public function scopeUserContainersTypes($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}
