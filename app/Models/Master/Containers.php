<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Support\Facades\Auth;
use App\Models\Containers\Movements;

class Containers extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'containers';
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


    public function containersOwner (){
        return $this->belongsto(ContinerOwnership::class,'container_ownership_id','id');
    }
    public function containersTypes (){
        return $this->belongsto(ContainersTypes::class,'container_type_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    public function movement()
    {
        return $this->hasMany(Movements::class ,'container_id','id');
    }
    public function activityLocation (){
        return $this->belongsto(Ports::class,'activity_location_id','id');
    }
    public function seller (){
        return $this->belongsto(LessorSeller::class,'description','id');
    }

    public function containerRepairs()
    {
        return $this->hasMany(ContainerRepairs::class ,'container_id','id');
    }
    public function scopeUserContainers($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;     
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }

    }
        
    public function createOrUpdateRepairs($inputs)
    {
        if (is_array($inputs) || is_object($inputs)){
            foreach($inputs as $input){
                $input['container_id'] = $this->id;
                if( isset($input['id']) ){
                    ContainerRepairs::find($input['id'])
                    ->update($input);
                }
                else{
                    ContainerRepairs::create($input);
                }
            }
        }
    }
}
