<?php

namespace App\Models\Containers;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Master\ContainersTypes;

class DemuragePeriodsSlabs extends Model
{
    use HasFilter;

    protected $table = 'demurrage_slabs';
    protected $guarded = [];

    public function demurrage()
    {
        return $this->belongsTo(Demurrage::class, 'demurage_id', 'id');
    }


    public function containersType(){
        return $this->belongsTo(ContainersTypes::class,'container_type_id','id');
    }

    public function demurageContainerType()
    {
        return $this->belongsTo(DemurageContainerType::class, 'demurrage_container_id');
    }


}
