<?php

namespace App\Models\Booking;

use App\Models\Master\Company;
use App\Models\Master\Ports;
use Illuminate\Database\Eloquent\Model;

class BookingRefNo extends Model
{
    protected $table = 'booking_ref_no';
    protected $guarded = [];



    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
    public function port(){
        return $this->belongsTo(Ports::class,'port_id','id');
    }
}
