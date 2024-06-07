<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call(SuperUserSeeder::class);
       $this->call(CountrySeeder::class);
       $this->call(CompanySeeder::class);
       $this->call(SettingsSeeder::class);
       $this->call(PermissionSeeder::class);
       $this->call(CustomerRoleTableSeeder::class);
       $this->call(VesselOperatorSeeder::class);
       $this->call(ContinerOwnershipTableSeeder::class);
       $this->call(ContinerStatusSeeder::class);
       $this->call(LegsSeeder::class);
       $this->call(BoundTableSeeder::class);
       $this->call(TriffSeeder::class);
       $this->call(CurrencySeeder::class);
        $this->call(ChargesMatricesSeeder::class);
    }
}
