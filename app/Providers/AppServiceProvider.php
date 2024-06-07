<?php

namespace App\Providers;

use App\Models\Master\Company;
use App\Models\PortCharge;
use App\Models\ViewModel\RootMenuNode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('components.soa-table', function ($view) {
            $portCharges = PortCharge::all();
            $view->with('portCharges', $portCharges);
        });
        
        Blade::directive('permission', function ($expression) {
            $permissionName = ucwords($expression);
            return "<?php if (Auth::user()->is_super_admin || app('laratrust')->can({$permissionName})) : ?>";

            if(Auth::user()->is_super_admin){
                return "<?php if (1) : ?>";
            }else{
                return "<?php if (app('laratrust')->can({$permissionName})) : ?>";
            }
        });
        Blade::directive('notpermission', function ($expression) {
            $permissionName = ucwords($expression);
            return "<?php if (!Auth::user()->is_super_admin && !app('laratrust')->can({$permissionName})) : ?>";
        });
        Blade::directive('endnotpermission', function ($expression) {
            return "<?php endif ?>";
        });
        view()->composer('layouts._sidebar', function ($view) {
            $rootMenu = [];
            if(Auth::check()){
                //$rootMenu = Auth::user()->getMenu();
            }
            $view->with('rootMenu', $rootMenu);
        });
        view()->composer('*', function ($view) {
            $companyId = null;
            if(Auth::check()){
                $companyId = Company::value('id');
            }
            $view->with('companyId', $companyId);
        });
        view()->composer(['layouts.app','layouts.auth'], function ($view) {
            $dir = "";
            if(App::isLocale("ar")){
                $dir = "rtl/";
            }
            $view->with('dir', $dir);
        });

    }
}
