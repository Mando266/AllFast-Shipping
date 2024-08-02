<?php

namespace App\Models\Voyages;

use App\Models\Bl\BlDraft;
use App\Models\Containers\Movements;
use App\Models\Master\Lines;
use App\Models\Master\Vessels;
use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;

class Voyages extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'voyages';
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
    public function line(){
        return $this->belongsTo(Lines::class,'line_id','id');
    }
    public function principal(){
        return $this->belongsTo(Lines::class,'principal_name','id');
    }
    public function leg(){
        return $this->belongsTo(Legs::class,'leg_id','id');
    }
    public function vessel(){
        return $this->belongsTo(Vessels::class,'vessel_id','id');
    }
    public function voyagePorts()
    {
        return $this->hasMany(VoyagePorts::class ,'voyage_id','id');
    }
    public function bldrafts()
    {
        return $this->hasMany(BlDraft::class ,'voyage_id','id');
    }
    public function xmlBldrafts() 
    {
        $voyageId = $this->id;

        return $this->bldrafts()
            ->whereHas('booking', function($query) use ($voyageId) {
                $query->where('voyage_id', $voyageId)
                    ->orWhere('voyage_id_second', $voyageId);
            })
            ->with('booking', 'blDetails.container');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class ,'voyage_id','id');
    }
    public function bookingSecondVoyage()
    {
        return $this->hasMany(Booking::class ,'voyage_id_second','id');
    }
    public function createOrUpdatevoyageport($inputs)
    {

        if (is_array($inputs) || is_object($inputs)){
            foreach($inputs as $input){
                $input['voyage_id'] = $this->id;
                if( isset($input['id']) ){
                    VoyagePorts::find($input['id'])
                    ->update($input);
                }
                else{
                    VoyagePorts::create($input);
                }
            }
        }
    }
}
