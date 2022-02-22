<?php

class Option
{
    public function __construct()
    {
    }

    public function getArchiveBrandLink()
    {
        return get_permalink($this->getOption('lb_archive_brand_link'));
    }

    public function getHeaderLinks()
    {
        $links = $this->getOption('lb_header_links');

        foreach ($links as $i => $link) {
            $items[] = [
                'type' => 'icon',
                'icon' => ['name' => $link['lb_header_links_icon']],
                'href' => get_permalink($link['lb_header_links_link']->ID)
            ];
        }

        return $items;
    }

    public function getLShopLinks()
    {
        $links = $this->getOption('lb_menu_shop_links');

        $items[] = ['type' => 'separator'];

        foreach ($links as $i => $link) {
            $items[] = [
                'type' => 'icon',
                'icon' => ['name' => $link['lb_menu_shop_links_icon']],
                'href' => get_permalink($link['lb_menu_shop_links_link']->ID)
            ];
        }

        return $items;
    }


    private function getOption($name)
    {
        return get_field($name, 'option');
    }
}
