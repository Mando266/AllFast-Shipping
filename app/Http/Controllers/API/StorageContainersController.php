<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class StorageContainersController extends Controller
{
    //
    public function getStorageBlContainers($id,$company_id)
    {
        $bl = BlDraft::where('id',$id)->first();
        $mov = Movements::where('bl_no', $bl->ref_no)->where('company_id',$company_id)->distinct()->get()->pluck('container_id')->toarray();

        $containers = Containers::whereIn('id',$mov)->get();

        return Response::json([
            'containers' => $containers
        ],200);
    }

    public function getStorageTriffs($service,$company_id)
    {
        $now = Carbon::now()->format('Y-m-d');
        $triffs = Demurrage::where('company_id', $company_id)->where('validity_to', '>=', $now)->where('tariff_type_id', $service)->with('tarriffType')->get();
        $data = [];
        foreach ($triffs as $triff) {
            $rowData = [
                'id' => $triff->id,
                'tariffTypeCode' => $triff->tarriffType->code,
                'tariffTypeDesc' => $triff->tarriffType->description,
                'portsCode' => optional($triff->ports)->code,
                'containersType'=>optional($triff->containersType)->name,
                'validfrom' => $triff->validity_from,
                'validto' => $triff->validity_to,
            ];
            $data[] = $rowData;
        }

        return Response::json([
            'triffs' => $data
        ],200);
    }
}
