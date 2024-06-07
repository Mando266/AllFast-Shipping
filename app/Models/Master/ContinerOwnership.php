<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ContinerOwnership extends Model
{
    protected $table = 'container_ownership';
    protected $guarded = [];

    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    
    public function scopeUserContainersOwnerShip($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}
