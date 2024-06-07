<?php

namespace App\Models\ViewModel;


class MenuNode
{
    public $item;
    protected $dbItems;
    protected $menuNodes;
    public function __construct($item,$dbItems)
    {
        $this->item = $item;
        $this->dbItems = $dbItems;
        $this->loadChilds();
    }

    public function loadChilds(){
        $subMenu = $this->dbItems->where('parent_id',$this->item->id);
        foreach($subMenu as $item){
            $this->menuNodes[] = new MenuNode($item,$this->dbItems);
        }
    }

    public function getChildNodes(){
        return $this->menuNodes ?? [];
    }
    public function hasChildNodes(){
        return count($this->getChildNodes()) > 0;
    }

    public function getRoute(){
        if($this->hasChildNodes()){
            return "#menu_".$this->item->id;
        }
        return is_null($this->item->route_name) ? "Javascript:void(0)" : route($this->item->route_name);
    }
    public function getDataAttrs(){
        return is_null($this->item->route_name) ? null :'data-link="true" data-menu='.$this->item->id.'';

    }
}
