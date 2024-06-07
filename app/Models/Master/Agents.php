<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Support\Facades\Auth;

class Agents extends Model implements PermissionSeederContract
{
    protected $table = 'agents';
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
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    
    public function scopeUserAgents($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}
