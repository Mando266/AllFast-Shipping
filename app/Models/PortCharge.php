<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortCharge extends Model
{
    protected $guarded = [];

    protected $appends = ['name'];
    
    protected $with = [
        'chargeMatrix'
    ];
    
    
    public function chargeMatrix(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChargesMatrix::class);
    }
    
    public function getNameAttribute()
    {
        return $this->chargeMatrix->name;
    }
}
