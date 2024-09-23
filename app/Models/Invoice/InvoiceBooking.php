<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceBooking extends Model
{
    
    protected $table = 'invoice_booking';
    protected $guarded = [];

    public function invoice(){
        return $this->belongsTo(Invoice::class,'Invoice_id','id');
    }
    
}