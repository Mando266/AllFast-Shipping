<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargesMatrix extends Model
{
    protected $guarded = [];

    public function portCharge()
    {
        return $this->hasOne(PortCharge::class, 'charge_matrix_id');
    }
}
