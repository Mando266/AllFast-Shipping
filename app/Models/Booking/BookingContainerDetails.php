<?php

namespace App\Models\Booking;

use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Traits\HasFilter;
use App\Models\Master\Ports;

use Illuminate\Database\Eloquent\Model;

class BookingContainerDetails extends Model
{
    use HasFilter;
    protected $table = 'booking_container_details';
    protected $guarded = [];

    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id','id');
    }
    public function activityLocation(){
        return $this->belongsTo(Ports::class,'activity_location_id','id');
    }
    public function container(){
        return $this->belongsTo(Containers::class,'container_id','id');
    }
    public function containerType(){
        return $this->belongsTo(ContainersTypes::class,'container_type','id');
    }
}
