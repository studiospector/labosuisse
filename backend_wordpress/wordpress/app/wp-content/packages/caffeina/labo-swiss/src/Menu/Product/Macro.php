<?php

namespace Caffeina\LaboSwiss\Menu\Product;

use Caffeina\LaboSwiss\Menu\Menu;

class Macro extends Menu
{
    private $macro = [];

    public function __construct()
    {
        $this->macro = $this->getTerms('product_cat', [
            'parent' => null,
            'exclude' => get_option('default_product_cat')
        ]);
    }

    public function get($device = 'desktop')
    {
        $items = [];

        foreach ($this->macro as $item) {
            $items[] = (new Area($item, $device))->get();
        }

        return $items;
    }
}
