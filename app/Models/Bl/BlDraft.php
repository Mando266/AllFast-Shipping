<?php

namespace App\Models\Bl;

use App\Models\Booking\Booking;
use App\Models\Invoice\Invoice;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use App\Models\Master\Lines;

use Illuminate\Database\Eloquent\Model;

class BlDraft extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'bl_draft';
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
    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id','id');
    }
    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type_id','id');
    }
    public function customer(){
        return $this->belongsTo(Customers::class,'customer_id','id');
    }
    public function additionalNotify(){
        return $this->belongsTo(Customers::class,'additional_notify_id','id');
    }
    public function customerNotify(){
        return $this->belongsTo(Customers::class,'customer_notifiy_id','id');
    }
    public function customerConsignee(){
        return $this->belongsTo(Customers::class,'customer_consignee_id','id');
    }
    public function voyage(){
        return $this->belongsTo(Voyages::class,'voyage_id','id');
    }
    public function placeOfAcceptence(){
        return $this->belongsTo(Ports::class,'place_of_acceptence_id','id');
    }
    public function placeOfDelivery(){
        return $this->belongsTo(Ports::class,'place_of_delivery_id','id');
    }
    public function loadPort(){
        return $this->belongsTo(Ports::class,'load_port_id','id');
    }
    public function dischargePort(){
        return $this->belongsTo(Ports::class,'discharge_port_id','id');
    }
    public function principal(){
        return $this->belongsTo(Lines::class,'principal_name','id');
    }
    public function childs()
    {
        return $this->hasMany(BlDraft::class ,'parent_id','id');
    }
    public function blDetails()
    {
        return $this->hasMany(BlDraftDetails::class ,'bl_id','id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class ,'bldraft_id','id');
    }

    public function UpdateBlDetails($inputs)
    {
        if (is_array($inputs) || is_object($inputs)){
        foreach($inputs as $input){

            $input['bl_id'] = $this->id;

            BlDraftDetails::find($input['id'])
            ->update($input);
        }
    }
}
}
