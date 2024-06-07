<?php

use Illuminate\Database\Seeder;

class BoundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bound')->truncate();
        $bounds = [
            ['name' => 'Import'],
            ['name' => 'Export'],
        ];
        DB::table('bound')->insert($bounds);    }
}
