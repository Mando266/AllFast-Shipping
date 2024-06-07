<?php

namespace App\Traits;

use ReflectionClass;
use ReflectionProperty;

trait ToJson{
    public function toJson(){
        $data = [];
        $stringKeys = [
            "documentTypeVersion",
            "postalCode",
            "branchID",
            "id",
            "taxpayerActivityCode",
            "salesOrderReference"
        ];
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($props as $prop) {
            if($prop->getName() === 'signatures'){
                continue;
            }
            $value = $prop->getValue($this);

            if( in_array($prop->getName() , $stringKeys)){
                $value = "".$value;
            }
            $data[$prop->getName()] = is_object($value) ? $value->toJson() : $value;
        }
        return $data;
    }
    public function toSignJson(){
        $data = [];
        $stringKeys = [
            "documentTypeVersion",
            "postalCode",
            "branchID",
            "id",
            "taxpayerActivityCode",
            "salesOrderReference"
        ];
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($props as $prop) {
            $value = $prop->getValue($this);
            if( in_array($prop->getName() , $stringKeys)){
                $value = "".$value;
            }
            $data[$prop->getName()] = is_object($value) ? $value->toJson() : $value;
        }
        $response = [
            'documents'=>[$data]
        ];
        return $response;
    }
}
