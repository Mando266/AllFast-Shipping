<?php

namespace App\Models\Containers;

use App\Models\Master\ContainersTypes;
use Illuminate\Database\Eloquent\Model;

class DemurageContainerType extends Model
{
    protected $table = 'demurage_container_type';
    protected $guarded = [];

    public function periods()
    {
        return $this->hasMany(DemuragePeriodsSlabs::class, 'demurrage_container_id');
    }

    public function containersType()
    {
        return $this->belongsTo(ContainersTypes::class, 'container_type_id');
    }
}
