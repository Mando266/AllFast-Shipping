<?php

namespace App\Models\Invoice;

use App\Models\Bl\BlDraft;
use App\Traits\HasFilter;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Models\Master\Customers;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'credit_notes';
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

    public function customer(){
        return $this->belongsTo(Customers::class,'customer_id','id');
    }

    public function bldraft(){
        return $this->belongsTo(BlDraft::class,'bl_no','id');
    }

    public function descriptions()
    {
        return $this->hasMany(CreditNoteDesc::class ,'credit_note_id','id');
    }

    public function createOrUpdateCreditNoteDesc($inputs)
    {

        if (is_array($inputs) || is_object($inputs)){
            foreach($inputs as $input){
                $input['credit_note_id'] = $this->id;
                if( isset($input['id']) ){
                    CreditNoteDesc::find($input['id'])
                    ->update($input);
                }
                else{
                    CreditNoteDesc::create($input);
                }
            }
        }
    }

}
