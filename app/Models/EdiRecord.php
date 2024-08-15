<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EdiRecord extends Model
{

    protected $fillable = [
        'container_no',
        'voyage_number',
        'imo_number',
        'ship_name',
        'country_code', 
        'gross_weight',
        'movement_type',
        'iso_number',
        'booking_number',
        'goods_description',
        'arrival_date',
        'departure_date',
        'activity_location',
        'pol',
        'pod',
    
    ];

    protected $dates = [
        'arrival_date',
        'departure_date',
    ];
}