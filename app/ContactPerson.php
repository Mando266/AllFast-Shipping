<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    protected $guarded = [];

    public function contactable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
    
}
