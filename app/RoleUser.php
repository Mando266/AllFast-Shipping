<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class RoleUser extends Model
{
    protected $table = 'role_user';
    protected $guarded = [];

    public function roles(){
        return $this->hasMany(Role::class ,'role_id','id');
    }
    public function users()
    {
        return $this->hasMany(User::class ,'user_id','id');
    }
}
