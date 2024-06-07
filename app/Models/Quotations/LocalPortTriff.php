<?php

namespace App\Models\Quotations;

use App\Models\Master\Agents;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Models\Quotations\LocalPortTriffDetailes;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Terminals;
use App\Models\Master\Country;
use App\Traits\HasFilter;

use Illuminate\Database\Eloquent\Model;

class LocalPortTriff extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'quotation_triff';
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

    public function agent(){
        return $this->belongsTo(Agents::class,'agent_id','id');
    }
    
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function port(){
        return $this->belongsTo(Ports::class,'port_id','id');
    }

    public function terminal(){
        return $this->belongsTo(Terminals::class,'terminal_id','id');
    }

    public function triffPriceDetailes()
    {
        return $this->hasMany(LocalPortTriffDetailes::class ,'quotation_triff_id','id');
    }

    
    public function createOrUpdateDetailes($inputs)
    {
        foreach($inputs as $input){
            
            $input['quotation_triff_id'] = $this->id;

            if( isset($input['id']) ){
                LocalPortTriffDetailes::find($input['id'])
                ->update($input);
            } 
            else{
                LocalPortTriffDetailes::create($input);
            }
        }
    }

}
