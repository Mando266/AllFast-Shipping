<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Master\Containers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CountriesController extends Controller
{ 
    public function getActivityContainers ($company_id,$equipment_id)
    {            
        $containers = Containers::
        //where('activity_location_id',$id)->where('status',2)->
        where('company_id',$company_id)
        ->where('container_type_id',$equipment_id)->
        select('id','code')->get();
        //dd($containers);
        return Response::json([
            'containers' => $containers
        ],200);
    }
}
