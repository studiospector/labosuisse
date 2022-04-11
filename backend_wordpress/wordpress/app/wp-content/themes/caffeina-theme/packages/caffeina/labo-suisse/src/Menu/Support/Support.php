<?php

namespace Caffeina\LaboSuisse\Menu\Support;

use Caffeina\LaboSuisse\Menu\Traits\HasGetMenu;

class Support
{
    use HasGetMenu;

    private $items = [];
    protected $menuPosition = 'lb_support_footer';

    public function __construct()
    {
        $this->items = $this->getItems();
    }

    public function get()
    {
        $menu = [
            'title' => __('Assistenza', 'labo-suisse-theme'),
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
