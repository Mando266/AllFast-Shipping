<?php

namespace App\Models\Trucker;

use Illuminate\Database\Eloquent\Model;

class DelegatedPerson extends Model
{
    protected $table = 'delegated_persons';
    protected $guarded = [];

    public function delegated()
    {
        return $this->belongsTo(Trucker::class,'trucker_id','id');
    }
}
