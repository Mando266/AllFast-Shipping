<?php

namespace App\Models;

use App\Models\Booking\Booking;
use App\Models\Master\Containers;
use Illuminate\Database\Eloquent\Model;

class PortChargeInvoiceRow extends Model
{
    protected $guarded = [];

//    protected $with = ['portCharge', 'invoice'];

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortChargeInvoice::class, 'port_charge_invoice_id');
    }

    public function portCharge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortCharge::class);
    }

    public function container(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Containers::class, 'container_no', 'code');
    }

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class, 'bl_no', 'ref_no');
    }

    public function voyage()
    {
        return $this->booking->voyage();
    }

    public function voyagePorts()
    {
        return $this->voyage->voyagePorts();
    }

    public function vessel()
    {
        return $this->booking->voyage->vessel();
    }

    public function getEtaAttribute()
    {
        return optional($this->voyagePorts->firstWhere('port_from_name', $this->invoice->port_id))->eta;
    }

    public function getServiceAttribute($value)
    {
        return $value === 'Select' ?
            '' :
            $value;
    }

    public function totalCosts(): array
    {
        $costs = [
            'thc' => $this->thc,
            'storage' => $this->storage,
            'power' => $this->power,
            'shifting' => $this->shifting,
            'disinf' => $this->disinf,
            'hand_fes_em' => $this->hand_fes_em,
            'gat_lift_off_inbnd_em_ft40' => $this->gat_lift_off_inbnd_em_ft40,
            'gat_lift_on_inbnd_em_ft40' => $this->gat_lift_on_inbnd_em_ft40,
            'pti' => $this->pti,
            'add_plan' => $this->add_plan,
            'additional_fees' => $this->additional_fees,
        ];

        $usdTotal = 0;
        $egpTotal = 0;

        foreach ($costs as $costName => $costValue) {
            $currencyField = $costName . '_currency';
            if (is_null($this->$currencyField)) {
                if ($this->invoice->invoice_egp > 0) {
                    $egpTotal += $costValue * $this->invoice->exchange_rate;
                } else {
                    $usdTotal += $costValue;
                }
            } elseif ($this->$currencyField === 'usd') {
                $usdTotal += $costValue;
            } else {
                $egpTotal += $costValue * $this->invoice->exchange_rate;
            }
        }

        return [$usdTotal, $egpTotal];
    }


}
