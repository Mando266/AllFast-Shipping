<?php
namespace Database\Seeders;

use App\TariffType;
use Illuminate\Database\Seeder;

class TariffTypeSeeder extends Seeder
{
    public function run()
    {
        $tariffTypes = [
            ['code' => 'EDET', 'description' => 'Export detention charges'],
            ['code' => 'ESTO', 'description' => 'Export Storage Charges'],
            ['code' => 'IDET', 'description' => 'Import Detention Charges'],
            ['code' => 'ISTO', 'description' => 'Import Storage Charges'],
            ['code' => 'EEST', 'description' => 'Empty Export Storage'],
            ['code' => 'IEST', 'description' => 'Empty Import Storage'],
            ['code' => 'PCEX', 'description' => 'Power Charges Export'],
            ['code' => 'PCIM', 'description' => 'Power Charges Import'],
        ];

        foreach ($tariffTypes as $tariffTypeData) {
            TariffType::firstOrCreate(
                ['code' => $tariffTypeData['code']],
                ['description' => $tariffTypeData['description']]
            );
        }
        return "tariff types seeded successfully";
    }
}
