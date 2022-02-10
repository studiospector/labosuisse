<?php
require_once(__DIR__ . '/basePage.php');

use pages\basePage;

class macro extends basePage
{
    private $levels = [
        1 => 'Macro',
        2 => 'Per Zona',
        3 => 'Per Esigenza',
        4 => 'Per Tipologia'
    ];

    public function __construct($name, $term)
    {
        parent::__construct('macro', $term);

        $level = get_category_parents_custom($term->term_id, 'product_cat');

        $payload =  get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id,
        ]);

        $items = [
            'type' => 'submenu',
            'label' => $term->name,
            'groupDescription' => $this->levels[$level + 1],
            'children' => $this->getSubItems($payload, $term->term_id)
        ];

        // die("<pre>" . json_encode($items, JSON_PRETTY_PRINT) . "</pre>");

        $this->setContext($payload);
        // $this->macro->render();
    }


    private function getSubItems($items, $parentId)
    {
        $menu = [];
        foreach ($items as $i => $item) {

            $level = get_category_parents_custom($item->term_id, 'product_cat');

            $menu[$i] = [
                'type' => 'link',
                'label' => $item->name,
                'href' => get_term_link($item),
            ];

            $children = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'parent' => $item->term_id,
            ]);

            if (count($children)) {
                $menu[$i]['type'] = 'submenu';
                unset($menu[$i]['href']);
                $menu[$i]['groupDescription'] = $this->levels[$level + 1];
                $menu[$i]['children'] = $this->getSubItems($children, $item->term_id);
            }
        }

        return $menu;
    }
}
