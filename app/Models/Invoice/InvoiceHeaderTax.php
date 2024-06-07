<?php

namespace App\Models\Invoice;

use App\Models\Tax\TaxType;
use Illuminate\Database\Eloquent\Model;

class InvoiceHeaderTax extends Model
{
    protected $table="invoice_header_taxes";
    protected $guarded = [];

    public function taxType(){
        return $this->belongsTo(TaxType::class,'tax_type','code');
    }
}
