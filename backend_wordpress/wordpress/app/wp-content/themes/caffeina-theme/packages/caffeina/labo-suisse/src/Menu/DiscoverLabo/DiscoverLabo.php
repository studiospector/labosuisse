<?php

namespace Caffeina\LaboSuisse\Menu\DiscoverLabo;

class DiscoverLabo
{
    private $items = [];

    public function __construct()
    {
        $this->items = $this->getItems();
    }
    public function get()
    {

        $menu = [
            'type' => 'submenu',
            'label' => __('Scopri Labo', 'labo-suisse-theme'),
            'children' => [
                [
                    'type' => 'submenu',
                    'label' => '',
                    'children' => []
                ],
            ],
            'fixed' => [
                [
                    'type' => 'card',
                    'data' => [
                        'images' => [
                            'original' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'large' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'medium' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                            'small' => get_template_directory_uri() . '/assets/images/card-img-5.jpg'
                        ],
                        'infobox' => [
                            'subtitle' => 'Magnetic Eyes',
                            'paragraph' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                            'cta' => [
                                'url' => '#',
                                'title' => __('Scopri di più', 'labo-suisse-theme'),
                                'variants' => ['quaternary']
                            ]
                        ],
                        'variants' => ['type-3']
                    ],
                ],
            ]
        ];

        foreach ($this->items as $item) {
            $menu['children'][0]['children'][] = [
                'type' => 'link',
                'label' => $item->title,
                'href' => $item->url,
            ];
        }


        return [$menu];
    }

    private function getItems()
    {
        $items = [];

        if (($locations = get_nav_menu_locations()) && isset($locations['lb_discover_labo'])) {
            $menu_obj = wp_get_nav_menu_object($locations['lb_discover_labo']);
            $items = wp_get_nav_menu_items($menu_obj->term_id);
        }

        return $items;
    }
}
