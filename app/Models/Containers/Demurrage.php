<?php

namespace App\Models\Containers;
use App\Models\Master\Terminals;
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
        return $this->belongsTo(Terminals::class,'terminal_id','id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function bound(){
        return $this->belongsTo(Bound::class,'bound_id','id');
    }
    public function slabs()
    {
        return $this->hasMany(DemurageContainerType::class,'demurage_id' ,'id');
    }
    public function periods()
    {
        return $this->hasMany(DemurageContainerTypeSlab::class, 'demurrage_container_id', 'id');
    }

    public function createOrUpdateSlabs($slabsData)
    {
        $slabs = DemurageContainerType::where('demurage_id', $this->id)->with('periods')->get();
        if (is_array($slabs) || is_object($slabs)){
            foreach ($slabs as $slab) {

                foreach($slab->periods as $period){
                }
            }
        }
    }
}
