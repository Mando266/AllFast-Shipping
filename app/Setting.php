<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $guarded = [];

    public static function getByKey($key){
        $item = Setting::where('name',$key)->first();
        if(!is_null($item)){
            return $item->getValue();
        }
        return null;
    }

    protected function getValue(){
        $castType = $this->cast_type ?? 'string';
        switch($castType){
            case 'bool':
            case 'boolean':
                return (bool)$this->value;
            default:
            return $this->value;
        }
    }
}
