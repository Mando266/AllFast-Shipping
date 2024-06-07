<?php

use Bitwise\PermissionSeeder\Seeder as PermissionSeederSeeder;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeder = new PermissionSeederSeeder();
        // $seeder->clearTable();
        $seeder->save();
    }
}
