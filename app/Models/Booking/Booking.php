<?php

namespace App\Models\Booking;

use App\Models\Bl\BlDraft;
use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Master\Terminals;
use App\Models\Quotations\Quotation;
use App\Models\Trucker\TruckerGates;
use App\Models\Voyages\Voyages;
use App\Traits\HasFilter;
use App\User;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'booking';
    protected $guarded = [];

    use PermissionSeederTrait;
    public function getPermissionActions()
    {
        return config('permission_seeder.actions', [
            'List',
            'Create',
            'Edit',
            'Delete',
        ]);
    }
    public function bldraft()
    {
        return $this->belongsTo(BlDraft::class, 'id', 'booking_id');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }
    public function equipmentsType()
    {
        return $this->belongsTo(ContainersTypes::class, 'equipment_type_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }
    public function forwarder()
    {
        return $this->belongsTo(Customers::class, 'ffw_id', 'id');
    }
    public function consignee()
    {
        return $this->belongsTo(Customers::class, 'customer_consignee_id', 'id');
    }
    public function customerNotify()
    {
        return $this->belongsTo(Customers::class, 'customer_notifiy_id', 'id');
    }
    public function voyage()
    {
        return $this->belongsTo(Voyages::class, 'voyage_id', 'id');
    }
    public function secondvoyage()
    {
        return $this->belongsTo(Voyages::class, 'voyage_id_second', 'id');
    }
    public function terminals()
    {
        return $this->belongsTo(Terminals::class, 'terminal_id', 'id');
    }
    public function polterminals()
    {
        return $this->belongsTo(Terminals::class, 'load_terminal_id', 'id');
    }
    public function placeOfAcceptence()
    {
        return $this->belongsTo(Ports::class, 'place_of_acceptence_id', 'id');
    }
    public function placeOfDelivery()
    {
        return $this->belongsTo(Ports::class, 'place_of_delivery_id', 'id');
    }
    public function placeOfReturn()
    {
        return $this->belongsTo(Ports::class, 'place_return_id', 'id');
    }
    public function pickUpLocation()
    {
        return $this->belongsTo(Ports::class, 'pick_up_location', 'id');
    }
    public function loadPort()
    {
        return $this->belongsTo(Ports::class, 'load_port_id', 'id');
    }
    public function dischargePort()
    {
        return $this->belongsTo(Ports::class, 'discharge_port_id', 'id');
    }
    public function bookedby()
    {
        return $this->belongsTo(User::class, 'booked_by', 'id');
    }
    public function agent()
    {
        return $this->belongsTo(Agents::class, 'bl_release', 'id');
    }
    public function container()
    {
        return $this->belongsTo(Containers::class, 'container_id', 'id');
    }
    public function bookingContainerDetails()
    {
        return $this->hasMany(BookingContainerDetails::class, 'booking_id', 'id');
    }
    public function principal()
    {
        return $this->belongsTo(Lines::class, 'principal_name', 'id');
    }
    public function operator()
    {
        return $this->belongsTo(Lines::class, 'vessel_name', 'id');
    }

    public function transhipmentPort()
    {
        return $this->belongsTo(Ports::class, 'transhipment_port', 'id');
    }

    public function truckerGates()
    {
        return $this->hasMany(TruckerGates::class, 'booking_id', 'id');
    }
        public function invoices()
        {
        return $this->belongsToMany(Invoice::class, 'invoice_booking', 'booking_id', 'invoice_id');
        }

    public function createOrUpdateContainerDetails($inputs)
    {
        $has_gate_in = 0;
        if (is_array($inputs) || is_object($inputs)) {
            foreach ($inputs as $input) {

                if ($input['container_id'] != null && $input['container_id'] != 000) {
                    $has_gate_in = 1;
                }

                $input['booking_id'] = $this->id;

                if (isset($input['id']) && !empty($input['id'])) {
                    $containerDetail = BookingContainerDetails::find($input['id']);

                    if ($containerDetail) {
                        $containerDetail->update($input);
                    } else {
                        \Log::warning('Container detail not found for update:', ['id' => $input['id']]);
                    }
                } else {
                    BookingContainerDetails::create($input);
                }
            }

            // Delete any removed container details
            $existingIds = collect($inputs)->pluck('id')->filter();
            BookingContainerDetails::where('booking_id', $this->id)
                ->whereNotIn('id', $existingIds)
                ->delete();
            $this->has_gate_in = $has_gate_in;
            $this->save();
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('company', function ($builder) {
            $builder->where('company_id', auth()->user()->company_id);
        });
    }
}