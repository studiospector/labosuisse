<?php

namespace Caffeina\LaboSuisse\Menu\Product;

use Caffeina\LaboSuisse\Menu\Traits\HasGetTerms;

class Macro
{
    use HasGetTerms;

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
