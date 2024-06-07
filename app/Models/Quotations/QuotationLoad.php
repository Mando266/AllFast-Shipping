<?php

namespace App\Models\Quotations;

use Illuminate\Database\Eloquent\Model;

class QuotationLoad extends Model
{
    protected $table = 'quotations_load';
    protected $guarded = [];

    public function quotation(){
        return $this->belongsTo(Quotations::class,'quotation_id','id');
    }
}
