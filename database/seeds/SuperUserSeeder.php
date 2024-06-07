<?php

use Illuminate\Database\Seeder;
use App\User;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('is_super_admin',1)->first();
        if(is_null($user)){
            User::create([
                'name'=>'sys_admin',
                'full_name'=>'System Admin',
                'password'=>Hash::make(123456),
                'email'=>'mfadl887@gmail.com',
                'is_active'=>1,
                'is_super_admin'=>1
            ]);
        }
    }
}
