<?php

namespace App\Http\Controllers;

use App\Models\ChargesMatrix;
use App\Models\PortCharge;
use Illuminate\Http\Request;

class SeedingController extends Controller
{
    public function seedChargesMatrices()
    {
        $dataSets = [
            [
                'name' => 'FULL-IMPORT',
                'empty' => false,
                'full' => true,
                'import' => true,
                'export' => false,
                'ts' => false,
                'payer' => 'LOCAL PAYER',
                'currency' => 'EGP EX',
                'storage_free' => 0,
                'storage_from' => 'DCHF',
                'storage_to' => 'SNTC',
                'power_free' => 0,
                'power_from' => 'DCHF',
                'power_to' => 'SNTC',
            ],
            [
                'name' => 'FULL-EXPORT',
                'empty' => true,
                'full' => false,
                'import' => false,
                'export' => true,
                'ts' => false,
                'payer' => 'LOCAL PAYER',
                'currency' => 'EGP EX',
                'storage_free' => 0,
                'storage_from' => 'RCVS',
                'storage_to' => 'LODF',
                'power_free' => 0,
                'power_from' => 'RCVS',
                'power_to' => 'LODF',
            ],
            [
                'name' => 'EMPTY-IMPORT',
                'empty' => true,
                'full' => false,
                'import' => true,
                'export' => false,
                'ts' => false,
                'payer' => 'FOREIGN PAYER',
                'currency' => 'USD',
                'storage_free' => 0,
                'storage_from' => 'NUL',
                'storage_to' => 'NUL',
                'power_free' => 0,
                'power_from' => 'NOT APP',
                'power_to' => 'NOT APP',
            ],
            [
                'name' => 'EMPTY-EXPORT',
                'empty' => true,
                'full' => false,
                'import' => false,
                'export' => true,
                'ts' => false,
                'payer' => 'FOREIGN PAYER',
                'currency' => 'USD',
                'storage_free' => 0,
                'storage_from' => 'NUL',
                'storage_to' => 'NUL',
                'power_free' => 0,
                'power_from' => 'NOT APP',
                'power_to' => 'NOT APP',
            ],
            [
                'name' => 'FULL-TRANSHIPMENT',
                'empty' => false,
                'full' => true,
                'import' => false,
                'export' => false,
                'ts' => true,
                'payer' => 'FOREIGN PAYER',
                'currency' => 'USD',
                'storage_free' => 0,
                'storage_from' => 'DCHT',
                'storage_to' => 'LODT',
                'power_free' => 0,
                'power_from' => 'DCHT',
                'power_to' => 'LODT',
            ],
            [
                'name' => 'EMPTY-TRANSHIPMENT',
                'empty' => true,
                'full' => false,
                'import' => false,
                'export' => false,
                'ts' => true,
                'payer' => 'FOREIGN PAYER',
                'currency' => 'USD',
                'storage_free' => 0,
                'storage_from' => 'DCTE',
                'storage_to' => 'LOTE',
                'power_free' => 0,
                'power_from' => 'NOT APP',
                'power_to' => 'NOT APP',
            ],
            [
                'name' => 'DA-INVENTORY',
                'empty' => false,
                'full' => false,
                'import' => false,
                'export' => false,
                'ts' => false,
                'payer' => 'FOREIGN PAYER',
                'currency' => 'USD',
                'storage_free' => 0,
                'storage_from' => 'DCTE',
                'storage_to' => 'LOTE',
                'power_free' => 0,
                'power_from' => 'NOT APP',
                'power_to' => 'NOT APP',
            ],
        ];

        $chargeMatrices = [];
        foreach ($dataSets as $data) {
            $chargeMatrices[] = ChargesMatrix::updateOrCreate(['name' => $data['name']], $data);
        }
        foreach ($chargeMatrices as $chargeMatrix){
            PortCharge::firstOrCreate([
                'charge_matrix_id' => $chargeMatrix->id
            ]);
        }
        dd('seeded Charges Matrices');
    }
}
