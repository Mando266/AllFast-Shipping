<?php

use Illuminate\Database\Seeder;
use App\Setting;
class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = Setting::updateOrCreate(
            ['name' => 'forget_password', 'value' => '1']
        );
        $model->update(['display_name' => 'Show Forget password', 'cast_type' => 'boolean']);
    }
}
