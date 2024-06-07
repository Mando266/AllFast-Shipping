<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currency')->truncate();
        $currency = [
            ['name' => 'USD'],
            ['name' => 'EGP'],
            ['name' => 'EUR'],
            ['name' => 'JPY'],
            ['name' => 'GBP'],
            ['name' => 'CHF'],
            ['name' => 'CAD'],
            ['name' => 'AED'],
            ['name' => 'UAE'],
            ['name' => 'KWD'],
            ['name' => 'JOD'],
            ['name' => 'SAR'],
            ['name' => 'OMR'],
            ['name' => 'BHD'],
            ['name' => 'ALL'],
            ['name' => 'AMD'],
            ['name' => 'ANG'],
            ['name' => 'INR'],
            ['name' => 'AOA'],
            ['name' => 'ARS'],
            ['name' => 'AUD'],
            ['name' => 'AWG'],
            ['name' => 'HKD'],
            ['name' => 'IDR'],
            ['name' => 'ISK'],
            ['name' => 'QAR'],
            ['name' => 'RUB'],
            ['name' => 'RWF'],
            ['name' => 'SZL'],
            ['name' => 'TRY'],
            ['name' => 'TND'],

        ];
        DB::table('currency')->insert($currency);
    }
}
