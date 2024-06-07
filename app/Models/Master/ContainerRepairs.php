<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ContainerRepairs extends Model 
{
    protected $table = 'container_repair';
    protected $guarded = [];

    public function containers (){
        return $this->belongsto(Containers::class,'container_id','id');
    }
}
