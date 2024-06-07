<?php

use Illuminate\Database\Seeder;

class VesselOperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vessels_operator')->truncate();
        $vessels_operators = [
            ['name' => 'Mfc Cargo Container Concept'],        
            ['name' => 'COSCO Shipping Line'],
            ['name' => 'Oceanbox Containers Ltd'],
            ['name' => 'Global Feeder Shipping'],
            ['name' => 'OOCL'],
            ['name' => 'ONE (OCEAN NETWORK EXPRES)'],        
            ['name' => 'Hapag-Lloyd'],
            ['name' => 'Altun'],
            ['name' => 'Worms'],
        ];

		DB::table('vessels_operator')->insert($vessels_operators);
        }
}
