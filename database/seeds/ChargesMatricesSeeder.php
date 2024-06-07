<?php

use App\Models\ChargesMatrix;
use Illuminate\Database\Seeder;

class ChargesMatricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataSets = [
            [
                'name' => 'FULL-IMPORT',
                'empty' => true,
                'full' => false,
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
                'empty' => false,
                'full' => true,
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
                'empty' => false,
                'full' => true,
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
                'empty' => false,
                'full' => true,
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
                'empty' => true,
                'full' => false,
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
                'empty' => false,
                'full' => true,
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
        ];

        foreach ($dataSets as $data) {
            ChargesMatrix::firstOrCreate($data);
        }
    }
}
