<?php

namespace App\Models\Master;
use Illuminate\Support\Facades\Auth;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class Vessels extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'vessels';
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

    public function VesselType (){
        return $this->belongsto(VesselType::class,'vessel_type_id','id');
    }
    public function VesselOperators (){
        return $this->belongsto(VesselOperators::class,'operator_id','id');
    }
    public function country (){
        return $this->belongsto(Country::class,'country_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }

    public function scopeUserVessels($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }

    }
}
