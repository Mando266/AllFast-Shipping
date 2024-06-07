<?php

namespace App\Models\Invoice\ViewModel;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class InvoiceHeaderTax extends Model
{
    use HasFilter;
    protected $table = 'V_Invoice_Header_Tax';
}
