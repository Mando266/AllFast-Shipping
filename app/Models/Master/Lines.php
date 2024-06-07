<?php

namespace App\Models\Master;

use App\Traits\HasContactPeople;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Support\Facades\Auth;

class Lines extends Model implements PermissionSeederContract
{
    use HasContactPeople;
    use PermissionSeederTrait;
    
    protected $table = 'line';
    protected $guarded = [];
    
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete'
        ]);
    }

    public function LineTypes(){
        return $this->belongsTo(LinesType::class,'line_type_id','id');
    }

    public function company(){
        return $this->belongsto(Company::class,'company_id','id');
    }
    
    public function types(){
        return $this->hasMany(LinesWithType::class,'line_id','id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function scopeUserLines($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}
