<?php
require_once(__DIR__ . '/basePage.php');
require_once(__DIR__ . '/zona.php');

use pages\basePage;

class macro extends basePage
{
    public function __construct($name, $term)
    {
        parent::__construct('macro', $term);

        // self::getAll();
        $payload =  get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id,
        ]);

        $this->setContext($payload);
        // $this->macro->render();
    }

    public static function getAll()
    {
        $macro = self::getProductCategory();

        $items = [];

        foreach ($macro as $item) {
            $items[] = zona::getAll($item);
        }

        return $items;
    }
}
