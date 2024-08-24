<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Master\Agents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AgentCountry extends Controller
{
    //
    public function getAgentCountry($company_id,$id)
    {
        $agents = Agents::where('company_id',$company_id)->where('country_id',$id)->where('is_active',1)->select('name','id')->get();
        
        return Response::json([
            'agents' => $agents
        ],200);
    }
}
