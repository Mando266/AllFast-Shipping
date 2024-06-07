<?php

use Illuminate\Database\Seeder;

class LegsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('legs')->truncate();
        $legs = [
            ['name' => 'EAST BOUND'],
            ['name' => 'NORTH BOUND'],
            ['name' => 'SOUTH BOUND'],
            ['name' => 'WEST BOUND'],

        ];
        DB::table('legs')->insert($legs);

        }
}
