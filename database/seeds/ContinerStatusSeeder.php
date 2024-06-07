<?php

use Illuminate\Database\Seeder;

class ContinerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('containers_status')->truncate();
        $containers_status = [
            ['name' => 'Full'],
            ['name' => 'Empty'],        
        ];

		DB::table('containers_status')->insert($containers_status);
    }
}
