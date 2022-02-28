<?php

namespace Caffeina\LaboSuisse\Menu\Product;

use Caffeina\LaboSuisse\Menu\Traits\HasGetTerms;

class Need
{
    use HasGetTerms;

    private $needs = [];
    private $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;

        $this->needs = $this->getTerms('product_cat', [
            'parent' => $parent->term_id
        ]);
    }

    public function get()
    {
        $items = [['type' => 'link', 'label' => 'Tutte le esigenze', 'href' => get_term_link($this->parent)]];

        foreach ($this->needs as $need) {
            $items[] = [
                'type' => 'link',
                'label' => $need->name,
                'href' => get_term_link($need),
            ];
        }

        return $items;
    }
}
