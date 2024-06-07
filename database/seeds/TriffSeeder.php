<?php

use Illuminate\Database\Seeder;

class TriffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('triff_kind')->truncate();
        $triffs = [
            ['name' => ' Standard Tariff'],
            ['name' => ' Customer Tariff'],
        ];
        DB::table('triff_kind')->insert($triffs);
    }
}
