<?php

use App\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $this->clearTable();
        Cache::flush();
        $levelZeroOrder = 1;
        // Menu::create([
        //     'name'=>'Dashboard',
        //     'parent_id'=>null,
        //     'icon_svg'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
        //     'class_name'=>null,
        //     'permission_name'=>'*',
        //     'display_order'=> $levelZeroOrder++,
        //     'route_name'=>'home'
        // ]);
        /**
         * Admin Menu
         */

        $this->seedAdminMenu( $levelZeroOrder++);
        /**
         * Master Data Menu
         */
        $menu = Menu::create([
            'name'=>'Master Data',
            'parent_id'=>null,
            'icon_svg'=>null,
            'class_name'=>null,
            'permission_name'=>null,
            'display_order'=> $levelZeroOrder++,
            'route_name'=>null
        ]);
        /** General Menu */
        $levelOneOrder = 1;
        $this->seedGeneralMenu($menu,$levelOneOrder++);


        /** Items Menu */
        // $subMenu = Menu::create([
        //     'name'=>'General',
        //     'parent_id'=>$menu->id,
        //     'icon_svg'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
        //     'class_name'=>null,
        //     'permission_name'=>null,
        //     'display_order'=>1,
        //     'route_name'=>null
        // ]);
    }

    protected function seedAdminMenu($order){
        $menu = Menu::create([
            'name'=>'Administration',
            'parent_id'=>null,
            'icon_svg'=>null,
            'class_name'=>null,
            'permission_name'=>null,
            'display_order'=>$order,
            'route_name'=>null
        ]);
        $subOrder = 1;
        Menu::create([
            'name'=>'Roles / Permissions',
            'parent_id'=>$menu->id,
            'icon_svg'=>'<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
            'class_name'=>null,
            'permission_name'=>'Role-List',
            'display_order'=>$subOrder++,
            'route_name'=>'roles.index'
        ]);
        Menu::create([
            'name'=>'Users',
            'parent_id'=>$menu->id,
            'icon_svg'=>'<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
            'class_name'=>null,
            'permission_name'=>'User-List',
            'display_order'=>$subOrder++,
            'route_name'=>'users.index'
        ]);
    }

    protected function seedGeneralMenu($parentMenu,$order){
        $subMenu = Menu::create([
            'name'=>'General',
            'parent_id'=>$parentMenu->id,
            'icon_svg'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
            'class_name'=>null,
            'permission_name'=>null,
            'display_order'=>$order,
            'route_name'=>null
        ]);
        $subOrder = 1;
        // Menu::create([
        //     'name'=>'Companies',
        //     'parent_id'=>$subMenu->id,
        //     'icon_svg'=>null,
        //     'class_name'=>null,
        //     'permission_name'=>'Company-List',
        //     'display_order'=>$subOrder++,
        //     'route_name'=>'company.index'
        // ]);

    }
    public function clearTable(){
        $tableName = 'menus';
        DB::table($tableName)->delete();
        if(env('DB_CONNECTION') == 'sqlsrv'){
            DB::unprepared("DBCC CHECKIDENT ($tableName, RESEED, 1) ");
        }else{
            DB::unprepared("ALTER TABLE {$tableName} AUTO_INCREMENT = 1; ");
        }
        return $this;
    }
}
