<?php

use App\Models\Master\Company;
use App\Models\Master\Country;
use App\Models\Master\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->truncate();
        $company = Company::create([
            'name'=>'Middle East Shipping Line',
            'country_id'=>Country::where('prefix','EG')->value('id'),
        ]);
    }
}
