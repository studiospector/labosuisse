<?php

namespace Caffeina\LaboSuisse\Menu\Traits;

trait HasGetMenu
{
    private function getItems()
    {
        $items = [];

        if (($locations = get_nav_menu_locations()) && isset($locations[$this->menuPosition])) {
            $menu_obj = wp_get_nav_menu_object($locations[$this->menuPosition]);
            $items = wp_get_nav_menu_items($menu_obj->term_id);
        }

        return $items;
    }
}
