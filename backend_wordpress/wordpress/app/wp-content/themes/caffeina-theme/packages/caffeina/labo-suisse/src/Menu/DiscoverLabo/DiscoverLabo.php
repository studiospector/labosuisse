<?php

namespace Caffeina\LaboSuisse\Menu\DiscoverLabo;

use Caffeina\LaboSuisse\Menu\Traits\HasGetMenu;
use Caffeina\LaboSuisse\Option\Option;

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
            'fixed' => [ (new Option())->getMenuFixedCard()]
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
