<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public static function getCompanyId(){
        return Company::value('id');
    }
}
