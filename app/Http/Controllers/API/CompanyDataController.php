<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Finance\AccountPeriod;
use App\Models\Finance\OperationRate;
use App\Models\Invoice\Invoice;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use App\Models\Master\Terminals;
use Illuminate\Support\Facades\Response;
use App\Models\Master\Lines;

class CompanyDataController extends Controller
{
    public function getVesselVoyages($id)
    {
        $voyages = Voyages::where('vessel_id', $id)->get();
        foreach ($voyages as $voyage) {
            $rowData = [
                'id' => $voyage->id,
                'voyage_no' => $voyage->voyage_no,
                'leg' => optional($voyage->leg)->name,
            ];
            $data[] = $rowData;
        }
        return Response::json([
            'voyages' => $data,
        ], 200);
    }

    public function getMultiVesselVoyages($ids): \Illuminate\Http\JsonResponse
    {
        $idArray = explode(',', $ids);

        $voyages = Voyages::whereIn('vessel_id', $idArray)->get()->load('leg', 'vessel');
        return Response::json([
            'voyages' => $voyages,
        ], 200);
    }

    public function portsCountry($id)
    {
        $ports = Ports::where('country_id', $id)->select('name', 'id')->get();

        return Response::json([
            'ports' => $ports,
        ], 200);
    }

    public function terminalsPorts($id)
    {
        $terminals = Terminals::where('port_id', $id)->select('name', 'id', 'code')->get();

        return Response::json([
            'terminals' => $terminals,
        ], 200);
    }

    public function customer($id)
    {
        $customer = Customers::where('id', $id)->get();

        return Response::json([
            'customer' => $customer,
        ], 200);
    }

    public function blinvoice($id)
    {
        $invoices = Invoice::where('invoice_status', 'confirm')->where('bldraft_id', $id)->where(
            'paymentstauts',
            0
        )->get();

        return Response::json([
            'invoices' => $invoices,
        ], 200);
    }

    public function customerInvoice($id)
    {
        $invoices = Invoice::where('invoice_status', 'confirm')->where('customer_id', $id)->where(
            'paymentstauts',
            0
        )->get();

        return Response::json([
            'invoices' => $invoices,
        ], 200);
    }

    public function oprator($id, $company_id)
    {
        $lines = Lines::where('company_id', $company_id)
            ->whereHas('types.type', function ($query) use ($id) {
                $query->where('type_id', $id);
            })
            ->with('types.type')
            ->get();

        return response()->json([
            'lines' => $lines,
        ], 200);
    }
}
