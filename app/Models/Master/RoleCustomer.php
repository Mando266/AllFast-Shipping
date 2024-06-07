<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RoleCustomer extends Model
{
    protected $table = 'role_cutomer';
    protected $guarded = [];

    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }

    public function scopeUserCustomerRoles($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}
