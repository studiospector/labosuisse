<?php

if (function_exists('register_nav_menus')) {
    register_nav_menus(
      array(
          'header' => 'Menu Header',
          'footer' => 'Menu Footer',
      )
  );
}

function buildTree(array &$elements, $parentId = 0)
{
    $branch = array();
    foreach ($elements as &$element) {
        if ($element->menu_item_parent == $parentId) {
            $children = buildTree($elements, $element->ID);
            if ($children) {
                $element->children = $children;
                $element->has_children = 1;
            }
            $branch[$element->ID] = $element;
            unset($element);
        }
    }

    return $branch;
}

function cleanTree($items)
{
    $fields = ['url', 'title', 'post_name', 'object'];

    $menu = array();

    foreach ($items as $item) {
        $menuItem = new stdClass();

        if (isset($item->children)) {
            $menuItem->children = cleanTree($item->children);
        }

        foreach ($fields as $field) {
            $menuItem->{$field} = $item->$field;
        }

        $menu[] = $menuItem;
    }

    return $menu;
}

function api_getAllMenus()
{
    $menus = array();
    $locations = get_nav_menu_locations();

    foreach ($locations as $location => $menu_id) {
        $name = wp_get_nav_menu_name($location);
        $items = wp_get_nav_menu_items($menu_id);
        $menuTree = buildTree($items);
        $menus[$location] = cleanTree($menuTree);
    }

    return (object) $menus;
}

// add_action('rest_api_init', function () {
//     register_rest_route('/v1', '/menus', array(
//         'methods' => 'GET',
//         'callback' => 'api_getAllMenus',
//     ));
// });
