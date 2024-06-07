<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quotations\LocalPortTriff;
use App\Models\Quotations\LocalPortTriffDetailes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class PriceController extends Controller
{
    //
    public function getLoadAgentPrice($id,$equipment_id = null,$company_id)
    {
        $date = now()->format('Y-m-d');
        $agentTriff = LocalPortTriff::where('company_id',$company_id)->where('agent_id',$id)->where('validity_to', '>=',$date)->pluck('id')->first();
        if($equipment_id != null){
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 0)->where('add_to_quotation', 1)->where(function ($query) use($equipment_id){
                $query->where('equipment_type_id', $equipment_id)
                      ->orWhere('equipment_type_id', 100);
            })->with('equipmentsType')->get();
        }else{
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 0)->where('add_to_quotation', 1)->with('equipmentsType')->get();
        }
        foreach ($agentTriff as $agentTrif) {
            $rowData = $agentTrif->toArray();
            $rowData['charge_type'] = optional($agentTrif->charge)->name;
            $data[] = $rowData;
        }
        return Response::json([
            'agentTriff' => $data
        ],200);
    }
    public function getDischargeAgentPrice($id,$equipment_id = null,$company_id)
    {
        $date = now()->format('Y-m-d');
        $agentTriff = LocalPortTriff::where('company_id',$company_id)->where('agent_id',$id)->where('validity_to', '>=',$date)->pluck('id')->first();
        if($equipment_id != null){
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 1)->where('add_to_quotation', 1)->where(function ($query) use($equipment_id){
                $query->where('equipment_type_id', $equipment_id)
                      ->orWhere('equipment_type_id', 100);
            })->with('equipmentsType')->get();
        }else{
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 1)->where('add_to_quotation', 1)->with('equipmentsType')->get();
        }   
        
        foreach ($agentTriff as $agentTrif) {
                $rowData = $agentTrif->toArray();
                $rowData['charge_type'] = optional($agentTrif->charge)->name;
                $data[] = $rowData;
        }
        return Response::json([
            'agentTriff' => $data
        ],200);
    }
    
}

