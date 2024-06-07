<?php

namespace App\Models\Containers;
use App\TariffType;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Country;
use App\Models\Containers\Bound;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Demurrage extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'demurrage';
    protected $guarded = [];

    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete'
        ]);
    }

    /**
     * @return BelongsTo
     */
    public function tarriffType(): BelongsTo
    {
        return $this->belongsTo(TariffType::class,'tariff_type_id','id');
    }
    public function containersType(){
        return $this->belongsTo(ContainersTypes::class,'container_type_id','id');
    }
    public function ports(){
        return $this->belongsTo(Ports::class,'port_id','id');
    }
    public function terminal(){
        return $this->belongsTo(Ports::class,'terminal_id','id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function bound(){
        return $this->belongsTo(Bound::class,'bound_id','id');
    }
}
