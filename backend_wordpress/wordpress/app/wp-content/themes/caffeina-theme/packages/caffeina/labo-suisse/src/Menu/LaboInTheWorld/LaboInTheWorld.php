<?php

namespace Caffeina\LaboSuisse\Menu\LaboInTheWorld;

use Caffeina\LaboSuisse\Menu\Traits\HasGetMenu;

class LaboInTheWorld
{
    use HasGetMenu;

    private $link = null;

    public function __construct()
    {
        $this->link = get_page_link(get_field('lb_labo_in_the_world_page', 'option'));
    }

    public function get($device = 'desktop')
    {
        return ($device == 'desktop')
            ? $this->desktop()
            : $this->mobile();
    }

    public function desktop()
    {
        $items = [
            [
                'type' => 'link',
                'label' => __('Labo nel Mondo', 'labo-suisse-theme'),
                'href' => $this->link,
            ]
        ];

        return $items;
    }

    public function mobile()
    {
        $items = [
            [
                'type' => 'link',
                'label' => __('Labo nel Mondo', 'labo-suisse-theme'),
                'href' => $this->link,
            ]
        ];

        return $items;
    }
}
