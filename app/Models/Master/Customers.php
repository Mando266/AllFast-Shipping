<?php

namespace App\Models\Master;

use App\ContactPerson;
use App\Models\Invoice\CreditNote;
use App\Models\Invoice\Invoice;
use App\Models\Receipt\Receipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use App\Traits\HasContactPeople;
use App\User;

class Customers extends Model implements PermissionSeederContract
{
    protected $table = 'customers';
    protected $guarded = [];
    
    use HasFilter;
    use PermissionSeederTrait;
    use HasContactPeople;
    
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete'
        ]);
    }
    public function CustomerRoles (){
        return $this->hasMany(CustomerRoles::class,'customer_id','id');
    }
    public function invoices (){
        return $this->hasMany(Invoice::class,'customer_id','id');
    }
    public function creditNotes (){
        return $this->hasMany(CreditNote::class,'customer_id','id');
    }
    public function refunds (){
        return $this->hasMany(Receipt::class,'customer_id','id')
            ->where('status','!=' ,'valid');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function User(){
        return $this->belongsTo(User::class,'sales_person_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    

    public function scopeUserCustomers($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
    public function createOrUpdateRoles($inputs)
    {
        if (is_array($inputs) || is_object($inputs)){
            foreach($inputs as $input){
                $input['customer_id'] = $this->id;
                if( isset($input['id']) ){
                    CustomerRoles::find($input['id'])
                    ->update($input);
                }
                else{
                    CustomerRoles::create($input);
                }
            }
        }
    }



}
