<?php

namespace App\Http\Controllers;

use App\Traits\HasCompany;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'List',
            'show' => 'Show',
            'create' => 'Create',
            'store' => 'Create',
            'edit' => 'Edit',
            'update' => 'Edit',
            'destroy' => 'Delete',
            'duplicate'=>'Create'
        ];
    }
    public function authorize($ability, $model,$instance=null)
    {
        if(!Auth::user()->is_active){
            Auth::logout();
            request()->session()->invalidate();
            return redirect()->route('login');
        }
        if(Auth::user()->is_super_admin){
            return true;
        }
        if($this->hasCompanyTrait($model) && !is_null($instance)){
            $companies = Auth::user()->getCompanies();
            if(! in_array(optional($instance)->company_id,$companies->pluck('id')->all())){
                throw new AuthorizationException("User is Unauthorized");
            };
        }
        $baseName = (new \ReflectionClass($model))->getShortName();
        $abilityMap = $this->resourceAbilityMap()[$ability];
        $ability = $baseName.'-'.$abilityMap;
        if(Auth::user()->can($ability)){
            return true;
        };
        throw new AuthorizationException("User is Unauthorized");
        // throw new UnauthorizedException();
    }
    protected function hasCompanyTrait($model){
        return in_array(HasCompany::class,class_uses($model));
    }

}
