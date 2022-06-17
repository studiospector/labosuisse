<?php

namespace Caffeina\LaboSuisse\Menu\DiscoverLabo;

use Caffeina\LaboSuisse\Menu\Traits\HasGetMenu;
use Caffeina\LaboSuisse\Option\Option;

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
            'title' => (new Option())->getFooterMenuTitles()['discoverLabo'],
            'items' => []
        ];

        foreach ($this->items as $item) {
            $menu['items'][] = [
                'title' => $item->title,
                'url' => $item->url,
                'target' => $item->target ? $item->target : '_self',
                'variants' => ['link', 'thin', 'small']
            ];
        }

        return $menu;
    }
}
