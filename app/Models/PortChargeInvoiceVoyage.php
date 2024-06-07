<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortChargeInvoiceVoyage extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'empty_costs' => 'array',
        'full_costs' => 'array',
    ];
}
