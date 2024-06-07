<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class LessorSeller extends Model
{
    protected $table = 'lessor_sellers';
    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
