<?php

namespace App\Models\Quotations;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ContainersTypes;

class QuotationDes extends Model
{
    protected $table = 'quotations_description';
    protected $guarded = [];

    public function quotation(){
        return $this->belongsTo(Quotations::class,'quotation_id','id');
    }

    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type_id','id');
    }
}
