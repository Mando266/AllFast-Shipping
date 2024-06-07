<?php

namespace App\Models\Trucker;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFilter;
use App\Models\Booking\Booking;

class TruckerGates extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'truckers_gate';
    protected $guarded = [];

    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete'
        ]); 
    }
    public function trucker()
    {
        return $this->belongsTo(Trucker::class,'trucker_id','id');
    }

    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id','id');
    }
}