<?php

namespace Caffeina\LaboSuisse\Menu\Impressum;

use Caffeina\LaboSuisse\Menu\Traits\HasGetMenu;

class Impressum
{
    use HasGetMenu;

    private $items = [];
    protected $menuPosition = 'lb_impressum_footer';

    public function __construct()
    {
        $this->items = $this->getItems();
    }

    public function get()
    {
        $menu = [];

        foreach ($this->items as $item) {
            $menu[] = [
                'title' => $item->title,
                'url' => $item->url,
                'target' => !empty($item->target) ? $item->target : '_self',
                'variants' => ['link', 'thin', 'small']
            ];
        }

        return $menu;
    }
}
