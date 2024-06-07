<?php

namespace App\Models\Invoice;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class InvoiceChargeDesc extends Model
{ 
    use HasFilter;
    protected $table = 'invoice_charge_description';
    protected $guarded = [];

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }

    public function charge(){
        return $this->belongsTo(ChargesDesc::class,'charge_description','name');
    }
}
