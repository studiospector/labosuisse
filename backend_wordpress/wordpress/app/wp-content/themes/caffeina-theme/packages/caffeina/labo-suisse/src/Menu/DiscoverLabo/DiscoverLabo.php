<?php

namespace Caffeina\LaboSuisse\Menu\DiscoverLabo;

use Caffeina\LaboSuisse\Menu\Traits\HasGetMenu;

class DiscoverLabo
{
    use HasGetMenu;

    private $items = [];
    protected $menuPosition = 'lb_discover_labo';

    public function __construct()
    {
        $this->items = $this->getItems();
    }

    public function get($device = 'desktop')
    {
        return ($device == 'desktop')
            ? $this->desktop()
            : $this->mobile();
    }

    public function desktop()
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
                        'type' => 'type-3',
                        'variants' => null
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

    public function mobile()
    {
        $items = [
            'type' => 'submenu',
            'label' => __('Scopri Labo', 'labo-suisse-theme'),
            'children' => []
        ];

        foreach ($this->items as $item) {
            $items['children'][] = [
                'type' => 'link',
                'label' => $item->title,
                'href' => $item->url,
            ];
        }

        return [$items];
    }
}
