<?php
namespace App\Filters\User;

use App\Filters\AbstractBasicFilter;

class RoleFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('roles',function($q)use($value){
            $q->whereIn('id',$value);
        });
    }
}
