<?php

namespace Caffeina\LaboSuisse\Menu\DiscoverLabo;

use Caffeina\LaboSuisse\Menu\Traits\HasGetMenu;

class DiscoverLaboFooter
{
    use HasGetMenu;

    private $items = [];
    protected $menuPosition = 'lb_discover_labo_footer';

    public function __construct()
    {
        $this->items = $this->getItems();
    }

    public function get()
    {
        $menu = [
            'title' => __('Scopri Labo', 'labo-suisse-theme'),
            'items' => []
        ];

        foreach ($this->items as $item) {
            $menu['items'][] = [
                'title' => $item->title,
                'url' => $item->url,
                'target' => '_self',
                'variants' => ['link', 'thin', 'small']
            ];
        }

        return $menu;
    }
}
