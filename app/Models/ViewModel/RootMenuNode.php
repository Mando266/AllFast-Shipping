<?php

namespace App\Models\ViewModel;

use Illuminate\Support\Facades\Auth;
use App\Menu;

class RootMenuNode
{
    protected $dbItems = [];
    public $menuNodes = [];
    public function __construct()
    {
        $this->dbItems = $this->loadMenusForUserRole();
        $this->loadChildNodes();
    }
    protected function loadMenusForUserRole(){
        if(!Auth::check()){return collect([]);}
        $whereQuery = "";
        if(Auth::check() && !Auth::user()->is_super_admin){
            $roles = Auth::user()->allPermissions()->pluck('name')->all();
            $whereQuery = "where permission_name in ( '".implode("','", $roles)."')";
        }

        return Menu::fromQuery("
        -- Get permission menus only
        ;with RECURSIVE menus_cte 
        as (
             select id,name,permission_name,icon_svg,route_name,class_name,parent_id,display_order
             from menus
             {$whereQuery}
            UNION ALL
            select menus.id,menus.name,menus.permission_name,menus.icon_svg,menus.route_name,menus.class_name,menus.parent_id,menus.display_order
            from menus
            inner join menus_cte on(menus_cte.parent_id = menus.id)
            )
        -- Get permission menus Tree
        , menus_tree
        as (
             select menus_cte.*,0 as level
                from menus_cte
                where parent_id is null
                UNION ALL
                select menus_cte.*,menus_tree.level +1
                from menus_cte
                inner join menus_tree on(menus_cte.parent_id = menus_tree.id)
         )

        select distinct(id) as unique_id,* from menus_tree
        order by menus_tree.parent_id,menus_tree.display_order;
        ");
    }

    protected function loadChildNodes(){
        $firstLevelNodes =  $this->dbItems->where('level',0);
        $this->loadMenuNodesChilds($firstLevelNodes);
    }

    protected function loadMenuNodesChilds($firstLevelNodes){
        foreach($firstLevelNodes as $item){
           $this->menuNodes[] = new MenuNode($item,$this->dbItems);
        }
    }
}
