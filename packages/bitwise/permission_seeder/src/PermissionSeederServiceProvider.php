<?php

namespace Bitwise\PermissionSeeder;

use Illuminate\Support\ServiceProvider;

class PermissionSeederServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->publishes([
            __DIR__.'/config/permission_seeder.php'=>config_path('permission_seeder.php'),
            __DIR__.'/seeds/PermissionSeeder.php'=>database_path('seeds/'.'PermissionSeeder.php'),
        ],'permission_seeder');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


}
