<?php

use Illuminate\Database\Seeder;

class ContinerOwnershipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('container_ownership')->truncate();
        $container_ownerships = [
            ['name' => 'OWNED CONTAINER'],
            ['name' => 'LEASED (PERIOD)'],
            ['name' => 'LEASED (ONE LEG)'],
            ['name' => 'SOC (SHIPPER OWNED CONTAINER)'],
            ['name' => 'SWAP'],
            ['name' => 'OTHER SHIPPING LINE'],

        ];
        DB::table('container_ownership')->insert($container_ownerships);

        }
}
