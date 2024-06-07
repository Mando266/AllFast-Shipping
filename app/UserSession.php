<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{   
    protected $table = 'user_sessions';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
