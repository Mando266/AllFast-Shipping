<?php

namespace App\Models\Master;
use Illuminate\Support\Facades\Auth;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class Terminals extends Model implements PermissionSeederContract
{
    protected $table = 'terminals';
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

    public function port(){
        return $this->belongsTo(Ports::class,'port_id','id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    public function scopeUserTerminals($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}
