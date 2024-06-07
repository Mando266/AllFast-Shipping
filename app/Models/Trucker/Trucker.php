<?php

namespace App\Models\Trucker;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;

class Trucker extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'truckers';
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
    public function delegatedPersons()
    {
        return $this->hasMany(DelegatedPerson::class ,'trucker_id','id');
    }
    public function createOrUpdateDelegatedPerson($inputs)
    {
        if (is_array($inputs) || is_object($inputs)){
            foreach($inputs as $input){
                $input['trucker_id'] = $this->id;
                if( isset($input['id']) ){
                    DelegatedPerson::find($input['id'])
                    ->update($input);
                }
                else{
                    DelegatedPerson::create($input);
                }
            }
        }
    }
}
